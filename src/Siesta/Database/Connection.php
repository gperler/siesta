<?php
declare(strict_types = 1);
namespace Siesta\Database;

use Siesta\Database\Exception\ConnectException;
use Siesta\Database\MetaData\DatabaseMetaData;

/**
 * @author Gregor Müller
 */
interface Connection
{

    /**
     * @param string $name
     */
    public function useDatabase(string $name): void;

    /**
     * returns the current database
     * @return string
     */
    public function getDatabase() : string;

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
    public function query(string $query) : ResultSet;

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function multiQuery(string $query) : ResultSet;

    /**
     * @param string $query
     *
     * @return void
     */
    public function execute(string $query): void;

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function executeStoredProcedure(string $query) : ResultSet;

    /**
     * @param string $value
     *
     * @return string
     */
    public function escape(string $value): string;

    /**
     * returns next sequence for given technical name
     *
     * @param string $technicalName
     *
     * @return int
     */
    public function getSequence(string $technicalName): int;

    /**
     * @return void
     */
    public function startTransaction(): void;

    /**
     * @return void
     */
    public function commit(): void;

    /**
     * @return void
     */
    public function rollback(): void;

    /**
     * @return void
     */
    public function close(): void;

    /**
     * @return void
     */
    public function enableForeignKeyChecks(): void;

    /**
     * @return void
     */
    public function disableForeignKeyChecks(): void;


    /**
     * @param string|null $databaseName
     *
     * @return DatabaseMetaData
     */
    public function getDatabaseMetaData(string $databaseName = null) : DatabaseMetaData;

    /**
     * @return MigrationStatementFactory
     */
    public function getMigrationStatementFactory() : MigrationStatementFactory;

    /**
     * @return CreateStatementFactory
     */
    public function getCreateStatementFactory() : CreateStatementFactory;

    /**
     * @return StoredProcedureFactory
     */
    public function getStoredProcedureFactory() : StoredProcedureFactory;

}