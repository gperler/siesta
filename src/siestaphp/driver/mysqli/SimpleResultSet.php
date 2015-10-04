<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 19:03
 */

namespace siestaphp\driver\mysqli;


use siestaphp\driver\ResultSet;
use siestaphp\runtime\DateTime;
use siestaphp\runtime\Factory;
use siestaphp\util\Util;

/**
 * Class SimpleResultSet
 * @package siestaphp\driver\mysqli
 */
class SimpleResultSet implements ResultSet
{
    /**
     *
     * @var array
     */
    protected $next;

    /**
     *
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
            $this->resultSet->close();
        }
    }


    /**
     * @param $key
     * @return bool|null
     */
    public function getBooleanValue($key) {
        $value = Util::getFromIndex($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return ($value !== 0);
    }


    /**
     * @param $key
     * @return int|null
     */
    public function getIntegerValue($key) {
        $value = Util::getFromIndex($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return (integer) $value;
    }


    /**
     * @param $key
     * @return float|null
     */
    public function getFloatValue($key){
        $value = Util::getFromIndex($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        return (float) $value;
    }


    /**
     * @param $key
     * @return null|string
     */
    public function getStringValue($key){
        return Util::getFromIndex($this->next, $key);
    }


    /**
     * @param $key
     * @return null|DateTime
     */
    public function getDateTime($key) {
        $value = Util::getFromIndex($this->next, $key);
        if (is_null($value)) {
            return null;
        }
        $dateTime = Factory::newDateTime();
        $dateTime->stringToTime($value);

        return $dateTime;
    }
}