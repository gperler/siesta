<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Model\StoredProcedure;

/**
 * @author Gregor Müller
 */
class MySQLDatabase implements DatabaseMetaData
{

    const SQL_GET_SP_LIST = "SELECT ROUTINE_NAME FROM information_schema.ROUTINES WHERE ROUTINE_TYPE = 'PROCEDURE' AND ROUTINE_SCHEMA = '%s';";

    const SQL_GET_TABLE_LIST = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='%s';";

    const ROUTINE_NAME = "ROUTINE_NAME";

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @var MySQLTable[]
     */
    protected array $tableList;

    /**
     * @var MySQLColumn[][]
     */
    protected array $columnList;

    /**
     * @var MySQLColumnReader
     */
    protected MySQLColumnReader $columnReader;

    /**
     * @var MySQLConstraintReader;
     */
    protected MySQLConstraintReader $referenceReader;

    /**
     * @var MySQLIndexReader;
     */
    protected MySQLIndexReader $indexReader;


    /**
     * MySQLDatabase constructor.
     *
     * @param Connection $connection
     * @param string|null $databaseName
     */
    public function __construct(Connection $connection, string $databaseName = null)
    {
        $this->connection = $connection;
        $this->tableList = [];
        if ($databaseName !== null) {
            $this->connection->useDatabase($databaseName);
        }
        $this->refresh();
    }


    /**
     *
     */
    public function refresh(): void
    {
        $this->tableList = [];
        $this->readMySQLTable();
        $this->readColumnList();
        $this->readConstraintList();
        $this->readIndexList();
    }


    /**
     *
     */
    private function readColumnList(): void
    {
        $this->columnReader = new MySQLColumnReader($this->connection, $this);
        foreach ($this->tableList as $tableName => $table) {
            $columnList = $this->columnReader->getColumnListByTableName($tableName);
            $table->setColumnList($columnList);
        }
    }


    /**
     *
     */
    private function readConstraintList(): void
    {
        $this->referenceReader = new MySQLConstraintReader($this->connection);
        foreach ($this->tableList as $tableName => $table) {
            $constraintList = $this->referenceReader->getConstraintListByTableName($tableName);
            $table->setConstraintList($constraintList);
        }
    }


    /**
     * @param string $tableName
     * @param string $constraintName
     *
     * @return MySQLConstraint|null
     */
    public function getConstraintByTableNameAndName(string $tableName, string $constraintName): ?MySQLConstraint
    {
        return $this->referenceReader->getConstraintByName($tableName, $constraintName);
    }


    /**
     *
     */
    private function readIndexList(): void
    {
        $this->indexReader = new MySQLIndexReader($this->connection, $this);
        foreach ($this->tableList as $tableName => $table) {
            $indexList = $this->indexReader->getIndexListByTableName($tableName);
            $table->setIndexList($indexList);
        }
    }


    /**
     * @return array
     */
    public function getTableList(): array
    {
        return $this->tableList;
    }


    /**
     * @param string $tableName
     *
     * @return TableMetaData|null
     */
    public function getTableByName(string $tableName): ?TableMetaData
    {
        return $this->tableList[$tableName] ?? null;
    }


    /**
     *
     */
    protected function readMySQLTable(): void
    {
        $tableDTOList = $this->getTableDTO();

        foreach ($tableDTOList as $tableDTO) {
            $mysqlTable = new MySQLTable($this->connection, $tableDTO);
            $this->tableList[$mysqlTable->getName()] = $mysqlTable;
        }
    }


    /**
     * @return TableDTO[]
     */
    protected function getTableDTO(): array
    {
        $tableDTOList = [];

        $sql = sprintf(self::SQL_GET_TABLE_LIST, $this->connection->getDatabase());
        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $tableDTOList[] = new TableDTO($resultSet);
        }
        $resultSet->close();

        return $tableDTOList;
    }


    /**
     * @return StoredProcedure[]
     */
    public function getStoredProcedureList(): array
    {
        $spList = [];
        $sql = sprintf(self::SQL_GET_SP_LIST, $this->connection->getDatabase());
        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $procedureName = $resultSet->getStringValue(self::ROUTINE_NAME);
            $spList[] = new MySQLStoredProcedure($procedureName);
        }
        foreach ($spList as $sp) {
            $sp->load($this->connection);
        }

        return $spList;
    }


}