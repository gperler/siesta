<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 15.10.15
 * Time: 15:00
 */

namespace siestaphp\naming;

/**
 * Class NamingService
 * @package siestaphp\naming
 */
class NamingService
{

    /**
     * @param string $tableName
     *
     * @return string
     */
    public static function getTableName($tableName) {
        return strtoupper($tableName);
    }

    /**
     * @param string $className
     *
     * @return string
     */
    public static function getClassName($className) {
        return ucfirst(strtolower($className));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function getDatabaseTypeName($type) {
        return strtoupper($type);
    }
}