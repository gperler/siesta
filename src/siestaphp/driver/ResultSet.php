<?php

namespace siestaphp\driver;

use siestaphp\runtime\SiestaDateTime;

/**
 * Interface ResultSet
 * @package siestaphp\driver
 */
interface ResultSet
{

    /**
     * checks if there are more ResultSets
     * @return boolean
     */
    public function hasNext();

    /**
     * gets the next ResultSet
     * @return ResultSet
     */
    public function getNext();

    /**
     * closes the database connection
     */
    public function close();

    /**
     * @param $key
     *
     * @return boolean
     */
    public function getBooleanValue($key);

    /**
     * @param $key
     *
     * @return integer
     */
    public function getIntegerValue($key);

    /**
     * @param $key
     *
     * @return float
     */
    public function getFloatValue($key);

    /**
     * @param $key
     *
     * @return string
     */
    public function getStringValue($key);

    /**
     * @param $key
     *
     * @return SiestaDateTime
     */
    public function getDateTime($key);
}