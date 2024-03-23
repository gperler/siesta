<?php
declare(strict_types=1);

namespace Siesta\Util;

/**
 * @author Gregor Müller
 */
class StringUtil
{
    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle): bool
    {
        return $needle === "" || mb_substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * @param $value
     * @param int|null $maxLength
     *
     * @return string|null
     */
    public static function trimToNull($value, int $maxLength = null): ?string
    {
        if ($value === null) {
            return null;
        }

        // preserve 0
        if ($value === 0 || $value === "0") {
            return "0";
        }

        // trim it
        $value = trim($value);

        if ($value === "") {
            return null;
        }

        if ($maxLength === 0 || $maxLength === null) {
            return $value;
        }
        return mb_substr($value, 0, $maxLength);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return string
     */
    public static function getEndAfterLast(string $haystack, string $needle): string
    {
        $lastOccurrence = strrchr($haystack, $needle);
        if ($lastOccurrence === false) {
            return $haystack;
        }
        return ltrim($lastOccurrence, $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return string
     */
    public static function getStartBeforeLast(string $haystack, string $needle): string
    {
        $end = self::getEndAfterLast($haystack, $needle);
        $length = -1 * (strlen($end) + strlen($needle));
        return mb_substr($haystack, 0, $length);
    }
}