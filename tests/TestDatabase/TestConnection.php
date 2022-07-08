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

    public function getDatabase(): string
    {
        // TODO: Implement getDatabase() method.
    }

    public function connect(ConnectionData $connectionData)
    {
        // TODO: Implement connect() method.
    }

    public function query(string $query): ResultSet
    {
        // TODO: Implement query() method.
    }

    public function multiQuery(string $query): ResultSet
    {
        // TODO: Implement multiQuery() method.
    }

    public function execute(string $query):void
    {

    }

    public function executeStoredProcedure(string $query): ResultSet
    {
        // TODO: Implement executeStoredProcedure() method.
    }

    public function escape(string $value):string
    {
        return '';
    }

    public function getSequence(string $technicalName):int
    {
        // TODO: Implement getSequence() method.
    }

    public function startTransaction():void
    {
        // TODO: Implement startTransaction() method.
    }

    public function commit():void
    {
        // TODO: Implement commit() method.
    }

    public function rollback():void
    {
        // TODO: Implement rollback() method.
    }

    public function enableForeignKeyChecks():void
    {
        // TODO: Implement enableForeignKeyChecks() method.
    }

    public function disableForeignKeyChecks():void
    {
        // TODO: Implement disableForeignKeyChecks() method.
    }

    public function close():void
    {

    }

    /**
     * @param string|null $databaseName
     * @return DatabaseMetaData
     * @throws \SiestaTest\TestUtil\TestException
     */
    public function getDatabaseMetaData(string $databaseName = null): DatabaseMetaData
    {
        return new TestDatabaseMetaData($this->fixtureFile);
    }

    public function getMigrationStatementFactory(): MigrationStatementFactory
    {
        return new TestMigrationStatementFactory();
    }

    public function getCreateStatementFactory(): CreateStatementFactory
    {
        return new TestCreateStatementFactory();
    }

    public function getStoredProcedureFactory(): StoredProcedureFactory
    {
        return new TestStoredProcedureFactory();
    }

}