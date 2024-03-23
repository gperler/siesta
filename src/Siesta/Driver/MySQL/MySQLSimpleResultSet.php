<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use mysqli_result;
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
    protected ?array $next;

    /**
     * @var mysqli_result
     */
    protected mysqli_result $resultSet;

    /**
     * MySQLSimpleResultSet constructor.
     *
     * @param mysqli_result $resultSet
     */
    public function __construct(mysqli_result $resultSet)
    {
        $this->resultSet = $resultSet;
    }

    /**
     * @return bool
     */
    public function hasNext() : bool
    {
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

    public function close(): void
    {
        $this->resultSet->free();
    }

    /**
     * @param string $key
     *
     * @return bool|null
     */
    public function getBooleanValue(string $key): ?bool
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
    public function getIntegerValue(string $key): ?int
    {
        $value = ArrayUtil::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return (integer)$value;
    }

    /**
     * @param string $key
     *
     * @return float|null
     */
    public function getFloatValue(string $key): ?float
    {
        $value = ArrayUtil::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return (float)$value;
    }

    /**
     * @param string $key
     *
     * @return null|string
     */
    public function getStringValue(string $key): ?string
    {
        return ArrayUtil::getFromArray($this->next, $key);
    }

    /**
     * @param string $key
     *
     * @return null|SiestaDateTime
     */
    public function getDateTime(string $key): ?SiestaDateTime
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
    public function getObject(string $key): mixed
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
    public function getArray(string $key): ?array
    {
        $value = $this->getStringValue($key);
        if ($value === null) {
            return null;
        }
        return json_decode($value, true);
    }

}