<?php

namespace siestaphp\util;

    /**
     * Created by PhpStorm.
     * User: gregor
     * Date: 21.06.15
     * Time: 14:11
     */

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

}