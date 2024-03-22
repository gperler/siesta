<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;

class MySQLColumnReader
{

    const SQL_GET_COLUMN_DETAILS = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '%s';";

    const COLUMN_TABLE_NAME = "TABLE_NAME";

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var MySQLDatabase
     */
    private MySQLDatabase $mySQLDatabase;

    /**
     * @var MySQLColumn[][]
     */
    private array $columnList;


    /**
     * MySQLColumnReader constructor.
     *
     * @param Connection $connection
     * @param MySQLDatabase $mySQLDatabase
     */
    public function __construct(Connection $connection, MySQLDatabase $mySQLDatabase)
    {
        $this->connection = $connection;
        $this->mySQLDatabase = $mySQLDatabase;
        $this->columnList = [];
        $this->readColumnList();
    }


    /**
     * @param string $tableName
     *
     * @return MySQLColumn[]
     */
    public function getColumnListByTableName(string $tableName): array
    {
        if (!isset($this->columnList[$tableName])) {
            return [];
        }
        return $this->columnList[$tableName];
    }


    /**
     *
     */
    private function readColumnList(): void
    {
        $sql = sprintf(self::SQL_GET_COLUMN_DETAILS, $this->connection->getDatabase());

        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $tableName = $resultSet->getStringValue(self::COLUMN_TABLE_NAME);
            $table = $this->mySQLDatabase->getTableByName($tableName);
            if ($table === null) {
                continue;
            }

            $column = new MySQLColumn();
            $column->fromResultSet($resultSet, $table->getAutoincrement());
            $this->addColumn($tableName, $column);
        }

        $resultSet->close();
    }


    /**
     * @param string $tableName
     * @param MySQLColumn $column
     */
    private function addColumn(string $tableName, MySQLColumn $column): void
    {
        if (!isset($this->columnList[$tableName])) {
            $this->columnList[$tableName] = [];
        }
        $columnName = $column->getDBName();
        $this->columnList[$tableName][$columnName] = $column;
    }
}