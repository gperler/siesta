<?php


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