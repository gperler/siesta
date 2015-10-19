<?php

namespace siestaphp\naming;

/**
 * Class NamingService
 * @package siestaphp\naming
 */
class NamingService
{

    /**
     * @param string $className
     *
     * @return string
     */
    public static function getClassName($className)
    {
        return ucfirst(strtolower($className));
    }

}