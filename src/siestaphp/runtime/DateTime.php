<?php


namespace siestaphp\runtime;

/**
 * Class DateTime
 * @package siestaphp\runtime
 */
interface DateTime
{

    /**
     * @return string
     */
    public function getSQLDateTime();

    /**
     * @return string
     */
    public function getSQLDate();

    /**
     * @return string
     */
    public function getSQLTime();

    /**
     * @param string $dateString
     */
    public function stringToTime($dateString);

    /**
     * @return int
     */
    public function getTime();

    /**
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function equals($dateTime);

}