<?php

namespace siestaphp\util;

use siestaphp\driver\Connection;
use siestaphp\runtime\SiestaDateTime;

/**
 * Class Util
 * @package siestaphp\util
 */
class Util
{

    /**
     * @param array $object
     * @param string $key
     *
     * @return string|null
     */
    public static function getFromArray($object, $key)
    {
        if ($object == null) {
            return null;
        }
        if (!isset ($object [$key])) {
            return null;
        }
        return $object [$key];
    }

    /**
     * @param string $object
     * @param string $key
     *
     * @return string
     */
    public static function getFromObject($object, $key)
    {
        if ($object == null) {
            return null;
        }
        if (!isset ($object->{$key})) {
            return null;
        }
        return $object->{$key};
    }

    /**
     * @param $variable
     * @param $type
     *
     * @return bool
     */
    public static function setType($variable, $type)
    {
        if ($variable === null) {
            return true;
        }

        switch ($type) {
            case "bool":
                settype($variable, $type);
                break;
            case "int":
                if (is_numeric($variable)) {
                    settype($variable, $type);
                    return true;
                } else {
                    return false;
                }
                break;

            case "float":
                if (is_numeric($variable)) {
                    settype($variable, $type);
                    return true;
                } else {
                    return false;
                }
                break;
        }

        return settype($variable, $type);
    }

    /**
     * @param SiestaDateTime $value
     *
     * @return string
     */
    public static function quoteDateTime($value)
    {
        if (is_null($value)) {
            return "NULL";
        }
        return "'" . $value->getSQLDateTime() . "'";
    }

    /**
     * @param SiestaDateTime $value
     *
     * @return string
     */
    public static function quoteDate($value)
    {
        if (is_null($value)) {
            return "NULL";
        }
        return "'" . $value->getSQLDate() . "'";
    }

    /**
     * @param SiestaDateTime $value
     *
     * @return string
     */
    public static function quoteTime($value)
    {
        if (is_null($value)) {
            return "NULL";
        }
        return "'" . $value->getSQLTime() . "'";
    }

    /**
     * @param Connection $connection
     * @param string $value
     *
     * @return string
     */
    public static function quoteEscape($connection, $value)
    {
        if ($value === 0 || $value === false) {
            return "'0'";
        }

        if ($value === null) {
            return "NULL";
        }
        return "'" . $connection->escape($value) . "'";
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public static function quoteNumber($value)
    {
        if ($value === 0 || $value === false) {
            return "'0'";
        }

        if ($value === null) {
            return "NULL";
        }
        $value = (float)$value;
        return "'" . $value . "'";
    }

}