<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;
use Siesta\Util\SiestaDateTime;

/**
 * @author Gregor Müller
 */
class MySQLSimpleResultSet implements ResultSet
{
    /**
     * @var array
     */
    protected $next;

    /**
     * @var \mysqli_result
     */
    protected $resultSet;

    /**
     * MySQLSimpleResultSet constructor.
     *
     * @param $resultSet
     */
    public function __construct(\mysqli_result $resultSet)
    {
        $this->resultSet = $resultSet;
    }

    /**
     * @return bool
     */
    public function hasNext() : bool
    {
        if (!$this->resultSet) {
            return false;
        }
        $this->next = $this->resultSet->fetch_assoc();

        return ($this->next !== null);
    }

    /**
     * @return array
     */
    public function getNext() : array
    {
        return $this->next;
    }

    public function close()
    {
        if ($this->resultSet != null) {
            $this->resultSet->free();
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