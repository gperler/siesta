<?php

namespace siestaphp\driver\mysqli;

use siestaphp\driver\ResultSet;
use siestaphp\runtime\SiestaDateTime;
use siestaphp\util\Util;

/**
 * Class SimpleResultSet
 * @package siestaphp\driver\mysqli
 */
class SimpleResultSet implements ResultSet
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
     * @param $resultSet
     */
    public function __construct($resultSet)
    {
        $this->resultSet = $resultSet;
    }

    /**
     * @return bool
     */
    public function hasNext()
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
    public function getNext()
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
     * @param $key
     *
     * @return bool|null
     */
    public function getBooleanValue($key)
    {
        $value = Util::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        $value = intval($value);
        return ($value !== 0);
    }

    /**
     * @param $key
     *
     * @return int|null
     */
    public function getIntegerValue($key)
    {
        $value = Util::getFromArray($this->next, $key);
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
    public function getFloatValue($key)
    {
        $value = Util::getFromArray($this->next, $key);
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
    public function getStringValue($key)
    {
        return Util::getFromArray($this->next, $key);
    }

    /**
     * @param $key
     *
     * @return null|SiestaDateTime
     */
    public function getDateTime($key)
    {
        $value = Util::getFromArray($this->next, $key);
        if (is_null($value)) {
            return null;
        }

        return new SiestaDateTime($value);
    }
}