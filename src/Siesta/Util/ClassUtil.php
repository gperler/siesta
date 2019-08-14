<?php

namespace Siesta\Util;

use ReflectionClass;
use ReflectionException;

class ClassUtil
{

    /**
     * @param string $className
     *
     * @return bool
     */
    public static function exists(string $className): bool
    {
        return class_exists($className);
    }

    /**
     * @param string $className
     * @param string $interfaceName
     * @return bool
     * @throws ReflectionException
     */
    public static function implementsInterface(string $className, string $interfaceName): bool
    {
        if (!self::exists($className)) {
            return false;
        }
        $reflectClass = new ReflectionClass($className);
        return $reflectClass->implementsInterface($interfaceName);
    }
}