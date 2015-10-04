<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 15.09.15
 * Time: 11:08
 */

namespace siestaphp\runtime;


use siestaphp\runtime\impl\DateTimeImpl;

/**
 * Class Factory
 * @package siestaphp\runtime
 */
class Factory {

    /**
     * @param string $dateString
     * @return DateTimeImpl
     */
    public static function newDateTime($dateString = null) {
        $date = new DateTimeImpl();
        $date->stringToTime($dateString);
        return $date;
    }

}