<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\Connection;
use Siesta\Database\ConnectionData;
use Siesta\Database\CreateStatementFactory;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Database\ResultSet;
use Siesta\Database\StoredProcedureFactory;
use Siesta\Util\File;
use SiestaTest\TestDatabase\MetaData\TestDatabaseMetaData;

class TestConnection implements Connection
{

    /**
     * @var File
     */
    protected $fixtureFile;

    public function setFixtureFile(File $file)
    {
        $this->fixtureFile = $file;
    }

    public function useDatabase(string $name)
    {
        // TODO: Implement useDatabase() method.
    }

    public function getDatabase() : string
    {
        // TODO: Implement getDatabase() method.
    }

    public function connect(ConnectionData $connectionData)
    {
        // TODO: Implement connect() method.
    }

    public function query(string $query) : ResultSet
    {
        // TODO: Implement query() method.
    }

    public function multiQuery(string $query) : ResultSet
    {
        // TODO: Implement multiQuery() method.
    }

    public function execute(string $query)
    {

    }

    public function executeStoredProcedure(string $query) : ResultSet
    {
        // TODO: Implement executeStoredProcedure() method.
    }

    public function escape(string $value)
    {
        // TODO: Implement escape() method.
    }

    public function getSequence(string $technicalName)
    {
        // TODO: Implement getSequence() method.
    }

    public function startTransaction()
    {
        // TODO: Implement startTransaction() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

    public function enableForeignKeyChecks()
    {
        // TODO: Implement enableForeignKeyChecks() method.
    }

    public function disableForeignKeyChecks()
    {
        // TODO: Implement disableForeignKeyChecks() method.
    }

    /**
     * @param string $databaseName here interpreted as file with model
     *
     * @return DatabaseMetaData
     */
    public function getDatabaseMetaData(string $databaseName = null) : DatabaseMetaData
    {
        return new TestDatabaseMetaData($this->fixtureFile);
    }

    public function getMigrationStatementFactory() : MigrationStatementFactory
    {
        return new TestMigrationStatementFactory();
    }

    public function getCreateStatementFactory() : CreateStatementFactory
    {
        return new TestCreateStatementFactory();
    }

    public function getStoredProcedureFactory() : StoredProcedureFactory
    {
        return new TestStoredProcedureFactory();
    }

}