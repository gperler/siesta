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
     * @return string|null
     */
    public static function getFromArray($object, $key = null)
    {
        if ($object == null) {
            return null;
        }
        if (!isset ($object [$key])) {
            return null;
        }
        return $object [$key];
    }
}