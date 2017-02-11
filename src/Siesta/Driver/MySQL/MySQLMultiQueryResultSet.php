<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;
use Siesta\Util\SiestaDateTime;

/**
 * @author Gregor Müller
 */
class MySQLMultiQueryResultSet implements ResultSet
{

    /**
     * @var \mysqli
     */
    private $connection;

    /**
     * @var \mysqli_result
     */
    private $mysqliResult;

    /**
     * @var array
     */
    protected $next;

    /**
     * @param \mysqli $connection
     */
    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
        $this->mysqliResult = $this->connection->store_result();
    }

    /**
     * @return bool
     */
    public function hasNext() : bool
    {
        // nothing in here return false
        if (!$this->mysqliResult) {
            return false;
        }

        // get next row
        $this->next = $this->mysqliResult->fetch_assoc();
        if ($this->next) {
            return true;
        }

        // close result set
        $this->mysqliResult->close();

        // no more results
        return false;
    }

    /**
     * @return array
     */
    public function getNext() : array
    {
        return $this->next;
    }

    /**
     *
     */
    public function close()
    {
        while ($this->connection->more_results()) {
            $this->connection->next_result();
            $resultSet = $this->connection->use_result();
            if ($resultSet !== false) {
                $resultSet->close();
            }
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function getBooleanValue(string $key)
    {
        $value = ArrayUtil::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        $value = intval($value);
        return ($value !== 0);
    }

    /**
     * @param string $key
     *
     * @return int|null
     */
    public function getIntegerValue(string $key)
    {
        $value = ArrayUtil::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return (integer)$value;
    }

    /**
     * @param $key
     *
     * @return float|null
     */
    public function getFloatValue(string $key)
    {
        $value = ArrayUtil::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return (float)$value;
    }

    /**
     * @param $key
     *
     * @return null|string
     */
    public function getStringValue(string $key)
    {
        return ArrayUtil::getFromArray($this->next, $key);
    }

    /**
     * @param $key
     *
     * @return null|SiestaDateTime
     */
    public function getDateTime(string $key)
    {
        $value = ArrayUtil::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }

        return new SiestaDateTime($value);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getObject(string $key)
    {
        $value = $this->getStringValue($key);
        if ($value === null) {
            return null;
        }
        return unserialize($value);
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key)
    {
        $value = $this->getStringValue($key);
        if ($value === null) {
            return null;
        }
        return json_decode($value, true);
    }

}