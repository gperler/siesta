<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\Connection;
use siestaphp\driver\CreateStatementFactory;
use siestaphp\generator\ReverseGeneratorConfig;

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
     * @var EntitySource[]
     */
    protected $entitySourceList;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $targetNamespace
     * @param string $targetPath
     *
     * @return EntitySource[]
     */
    public function getEntitySourceList($targetNamespace, $targetPath)
    {

        $this->extractEntitySourceList($targetNamespace, $targetPath);

        return $this->entitySourceList;

    }

    /**
     * @param string $targetNamespace
     * @param string $targetPath
     */
    private function extractEntitySourceList($targetNamespace, $targetPath)
    {
        $this->entitySourceList = [];
        $tableDTOList = [];

        // find tables first
        $sql = sprintf(self::SQL_GET_TABLE_LIST, $this->connection->getDatabase());
        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $tableDTOList[] = new TableDTO($resultSet);
        }
        $resultSet->close();

        // iterate tables and get data from database
        foreach ($tableDTOList as $tableDTO) {
            // do not care about sequencer table
            if ($tableDTO->name === CreateStatementFactory::SEQUENCER_TABLE_NAME) {
                continue;
            }
            $this->entitySourceList[] = new TableMetadata($this->connection, $tableDTO, $targetNamespace, $targetPath);
        }
    }

}