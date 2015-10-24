<?php

namespace siestaphp\driver\mysqli\replication;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLConnection;

/**
 * Class Replication
 * @package siestaphp\driver\mysqli\replication
 */
class Replication
{

    const REPLICATION = "replication";

    const REPLICATION_TABLE_SUFFIX = "_MEMORY";

    /**
     * @param $tableName
     *
     * @return string
     */
    public static function getReplicationTableName($tableName)
    {
        return $tableName . self::REPLICATION_TABLE_SUFFIX;
    }

    /**
     * @param EntityGeneratorSource $source
     *
     * @return bool
     */
    public static function isReplication(EntityGeneratorSource $source) {
        $dbSpecific = $source->getDatabaseSpecific(MySQLConnection::NAME);
        if (!$dbSpecific) {
            return false;
        }
        return $dbSpecific->getAttributeAsBool(self::REPLICATION);
    }

}