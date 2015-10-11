<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\Driver;

/**
 * Class DatabaseMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class DatabaseMetaData
{

    const SQL_GET_TABLE_LIST = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='%s';";

    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
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

        $this->driver->useDatabase($databaseName);

        $entitySourceList = array();
        $tableDTOList = array();

        $sql = sprintf(self::SQL_GET_TABLE_LIST, $databaseName);
        $resultSet = $this->driver->query($sql);
        while ($resultSet->hasNext()) {
            $tableDTOList[] = new TableDTO($resultSet);
        }
        $resultSet->close();

        foreach ($tableDTOList as $tableDTO) {
            $entitySourceList[] = new TableMetadata($this->driver, $tableDTO, $targetPath, $targetNamespace);
        }

        return $entitySourceList;

    }

}