<?php
declare(strict_types=1);

namespace Siesta\Util;

/**
 * @author Gregor Müller
 */
class ObjectUtil
{
    /**
     * @param string $object
     * @param string $key
     *
     * @return mixed
     */
    public static function getFromObject($object, $key): mixed
    {
        if ($object == null) {
            return null;
        }
        if (!isset ($object->{$key})) {
            return null;
        }
        return $object->{$key};
    }

}