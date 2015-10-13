<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\Connection;

/**
 * Class DatabaseMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class DatabaseMetaData
{

    const SQL_GET_TABLE_LIST = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='%s';";

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $databaseName
     * @param string $targetNamespace
     * @param string $targetPath
     *
     * @return EntitySource[]
     */
    public function getEntitySourceList($databaseName, $targetNamespace, $targetPath)
    {

        $this->connection->useDatabase($databaseName);

        $entitySourceList = array();
        $tableDTOList = array();

        $sql = sprintf(self::SQL_GET_TABLE_LIST, $databaseName);
        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $tableDTOList[] = new TableDTO($resultSet);
        }
        $resultSet->close();

        foreach ($tableDTOList as $tableDTO) {
            $entitySourceList[] = new TableMetadata($this->connection, $tableDTO, $targetPath, $targetNamespace);
        }

        return $entitySourceList;

    }

}