<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 19:02
 */

namespace siestaphp\runtime;


/**
 * Class DateTime
 * @package siestaphp\runtime
 */
interface DateTime {

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
     * @return bool
     */
    public function equals( $dateTime);

}