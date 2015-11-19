<?php

namespace siestaphp\util;

/**
 * Class StringUtil
 * @package siestaphp\util
 */
class StringUtil
{

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * @param $value
     * @param int $maxLength
     *
     * @return bool|int|null|string
     */
    public static function trimToNull($value, $maxLength = 0)
    {
        if ($value === null) {
            return null;
        }

        // preserve 0
        if ($value === 0) {
            return 0;
        }

        // preserve false
        if ($value === false) {
            return false;
        }

        // trim it
        $value = trim($value);

        if (!$value) {
            return null;
        }

        if ($maxLength === 0) {
            return $value;
        }
        return substr($value, 0, $maxLength);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return string
     */
    public static function getEndAfterLast($haystack, $needle)
    {
        return ltrim(strrchr($haystack, $needle), $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return string
     */
    public static function getStartBeforeLast($haystack, $needle)
    {
        $end = self::getEndAfterLast($haystack, $needle);
        return str_replace($needle . $end, "", $haystack);
    }

}