<?php
declare(strict_types = 1);

namespace Siesta\Util;

/**
 * @author Gregor Müller
 */
class ArrayUtil
{

    /**
     * @param array $object
     * @param string $key
     *
     * @return string|null|mixed
     */
    public static function getFromArray($object, $key = null): mixed
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
     * @param $array
     *
     * @return bool
     */
    public static function isArray($array): bool
    {
        return ($array !== null) && (is_array($array));
    }

}