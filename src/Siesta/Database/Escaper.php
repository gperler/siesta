<?php

declare(strict_types=1);

namespace Siesta\Database;

use Siesta\Contract\ArraySerializable;
use Siesta\Util\SiestaDateTime;

/**
 * @author Gregor Müller
 */
class Escaper
{

    const NULL = "NULL";

    /**
     * @param SiestaDateTime|null $dateTime
     *
     * @return string
     */
    public static function quoteDateTime(SiestaDateTime $dateTime = null): string
    {
        if ($dateTime === null) {
            return self::NULL;
        }
        return "'" . $dateTime->getSQLDateTime() . "'";
    }

    /**
     * @param SiestaDateTime|null $date
     *
     * @return string
     */
    public static function quoteDate(SiestaDateTime $date = null): string
    {
        if ($date === null) {
            return self::NULL;
        }
        return "'" . $date->getSQLDate() . "'";
    }

    /**
     * @param SiestaDateTime|null $time
     *
     * @return string
     */
    public static function quoteTime(SiestaDateTime $time = null): string
    {
        if ($time === null) {
            return self::NULL;
        }
        return "'" . $time->getSQLTime() . "'";
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public static function quoteBool($value): string
    {
        if ($value === null) {
            return self::NULL;
        }
        if ($value === "1" || $value === 1 || $value === true) {
            return "'1'";
        }
        return "'0'";
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public static function quoteInt($value): string
    {
        if ($value === 0 || $value === false) {
            return "'0'";
        }

        if ($value === null) {
            return self::NULL;
        }
        $value = (int)$value;

        return "'" . $value . "'";
    }

    /**
     * @param float $value
     *
     * @return string
     */
    public static function quoteFloat($value): string
    {
        if ($value === 0 || $value === false) {
            return "'0'";
        }

        if ($value === null) {
            return self::NULL;
        }
        $value = (float)$value;

        return "'" . $value . "'";
    }

    /**
     * @param Connection $connection
     * @param $value
     * @param int|null $maxLength
     * @return string
     */
    public static function quoteString(Connection $connection, $value, int $maxLength = null): string
    {
        if ($value === null) {
            return self::NULL;
        }

        if ($maxLength !== 0 && $maxLength !== null) {
            $value = mb_substr($value, 0, $maxLength);
        }

        return "'" . $connection->escape($value) . "'";
    }

    /**
     * @param Connection $connection
     * @param $object
     *
     * @return string
     */
    public static function quoteObject(Connection $connection, $object): string
    {
        if ($object === null) {
            return self::NULL;
        }
        return self::quoteString($connection, serialize($object));
    }

    /**
     * @param Connection $connection
     * @param array|null $array
     *
     * @return string
     */
    public static function quoteArray(Connection $connection, array $array = null): string
    {
        if ($array === null) {
            return self::NULL;
        }
        return self::quoteString($connection, json_encode($array));
    }

    /**
     * @param Connection $connection
     * @param ArraySerializable|null $arraySerializable
     *
     * @return string
     */
    public static function quoteArraySerializable(Connection $connection, ArraySerializable $arraySerializable = null): string
    {
        if ($arraySerializable === null) {
            return self::NULL;
        }
        return self::quoteArray($connection, $arraySerializable->toArray());
    }
}