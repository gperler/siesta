<?php

namespace siestaphp\tests\unit\connectionFactory;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ColumnMigrator;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionData;
use siestaphp\driver\CreateStatementFactory;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\driver\ResultSet;

/**
 * Class TestConnection
 * @package siestaphp\tests\unit\connectionFactory
 */
class TestConnection implements Connection
{

    /**
     * @var ConnectionData
     */
    public $connectionData;

    /**
     * @param ConnectionData $connectionData
     */
    public function __construct(ConnectionData $connectionData)
    {
        $this->connectionData = $connectionData;
    }

    /**
     * @param string $name
     */
    public function useDatabase($name)
    {
    }

    /**
     * returns the current database
     * @return string
     */
    public function getDatabase()
    {
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData)
    {
    }

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function query($query)
    {
    }

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function multiQuery($query)
    {
    }

    /**
     * @param $query
     *
     * @return \mysqli_result
     */
    public function execute($query)
    {
    }

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function executeStoredProcedure($query)
    {
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function escape($value)
    {
    }

    /**
     * returns next sequence for given technical name
     *
     * @param string $technicalName
     *
     * @return int
     */
    public function getSequence($technicalName)
    {
    }

    public function startTransaction()
    {
    }

    public function commit()
    {
    }

    public function rollback()
    {
    }

    public function enableForeignKeyChecks()
    {
    }

    public function disableForeignKeyChecks()
    {
    }

    /**
     * @param string $targetNamespace
     * @param string $targetPath
     *
     * @return EntitySource[]
     */
    public function getEntitySourceList($targetNamespace, $targetPath)
    {
    }

    /**
     * @return ColumnMigrator
     */
    public function getColumnMigrator()
    {
    }

    /**
     * @return CreateStatementFactory
     */
    public function getCreateStatementFactory()
    {
    }

}