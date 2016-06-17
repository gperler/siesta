<?php

namespace siestaphp\runtime;

use siestaphp\runtime\impl\DateTimeImpl;

/**
 * Class Factory
 * @package siestaphp\runtime
 */
class Factory
{

    /**
     * @param string $dateString
     *
     * @return DateTimeImpl
     */
    public static function newDateTime($dateString = null)
    {
        $date = new SiestaDateTime();
        if ($dateString !== null) {
            $date->stringToTime($dateString);
        }

        return $date;
    }

}