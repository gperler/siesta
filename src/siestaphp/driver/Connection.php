<?php

namespace siestaphp\driver;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\exceptions\ConnectException;

/**
 * Interface Connection
 * @package siestaphp\driver
 */
interface Connection
{

    /**
     * @param string $name
     */
    public function useDatabase($name);

    /**
     * returns the current database
     * @return string
     */
    public function getDatabase();

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData);

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function query($query);

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function multiQuery($query);

    /**
     * @param $query
     *
     * @return \mysqli_result
     */
    public function execute($query);

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function executeStoredProcedure($query);

    /**
     * @return TableBuilder
     */
    public function getTableBuilder();

    /**
     * @param string $value
     *
     * @return string
     */
    public function escape($value);

    /**
     * allows the driver to install needed artifacts. Invoked when generating entities
     */
    public function install();

    /**
     * returns next sequence for given technical name
     *
     * @param string $technicalName
     *
     * @return int
     */
    public function getSequence($technicalName);

    public function startTransaction();

    public function commit();

    public function rollback();

    public function enableForeignKeyChecks();

    public function disableForeignKeyChecks();

    /**
     * @param string $databaseName
     * @param string $targetNamespace
     * @param string $targetPath
     *
     * @return EntitySource[]
     */
    public function getEntitySourceList($databaseName = null, $targetNamespace = null, $targetPath = null);

    /**
     * @return ColumnMigrator
     */
    public function getColumnMigrator();
}