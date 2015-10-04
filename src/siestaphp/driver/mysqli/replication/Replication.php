<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 30.06.15
 * Time: 18:46
 */

namespace siestaphp\driver\mysqli\replication;

/**
 * Class Replication
 * @package siestaphp\driver\mysqli\replication
 */
class Replication {

    const REPLICATION_TABLE_SUFFIX = "_MEMORY";


    /**
     * @param $tableName
     * @return string
     */
    public static function getReplicationTableName($tableName) {
        return $tableName . self::REPLICATION_TABLE_SUFFIX;
    }


}