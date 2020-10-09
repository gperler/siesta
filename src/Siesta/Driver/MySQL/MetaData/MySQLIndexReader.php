<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;

class MySQLIndexReader
{

    const SQL_GET_INDEX_LIST = "SELECT S.* FROM INFORMATION_SCHEMA.STATISTICS AS S WHERE S.TABLE_SCHEMA = '%s';";

    const COLUMN_TABLE_NAME = "TABLE_NAME";


    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var MySQLDatabase
     */
    private $mySQLDatabase;

    /**
     * @var MySQLIndex[][]
     */
    private $indexList;


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
        $this->indexList = [];
        $this->readIndexList();
    }


    /**
     * @param string $tableName
     *
     * @return MySQLIndex[]
     */
    public function getIndexListByTableName(string $tableName): array
    {
        if (!isset($this->indexList[$tableName])) {
            return [];
        }
        return array_values($this->indexList[$tableName]);
    }


    /**
     * extracts index data
     */
    protected function readIndexList()
    {
        $sql = sprintf(self::SQL_GET_INDEX_LIST, $this->connection->getDatabase());

        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $indexName = $resultSet->getStringValue(MySQLIndex::INDEX_NAME);
            if ($indexName === MySQLIndex::PRIMARY_KEY_INDEX_NAME) {
                continue;
            }
            $tableName = $resultSet->getStringValue(self::COLUMN_TABLE_NAME);


            $constraint = $this->mySQLDatabase->getConstraintByTableNameAndName($tableName, $indexName);
            if ($constraint !== null) {
                continue;
            }

            $index = $this->getIndexByName($tableName, $indexName);
            if ($index === null) {
                $index = new MySQLIndex();
                $index->fromResultSet($resultSet);
                $this->addIndex($tableName, $index);
            } else {
                $index->addIndexPart($resultSet);
            }
        }
    }


    /**
     * @param string $tableName
     * @param MySQLIndex $mySQLIndex
     */
    private function addIndex(string $tableName, MySQLIndex $mySQLIndex)
    {
        if (!isset($this->indexList[$tableName])) {
            $this->indexList[$tableName] = [];
        }
        $indexName = $mySQLIndex->getName();
        $this->indexList[$tableName][$indexName] = $mySQLIndex;
    }


    /**
     * @param string $tableName
     * @param string $indexName
     *
     * @return MySQLConstraint|null
     */
    private function getIndexByName(string $tableName, string $indexName)
    {
        if (!isset($this->indexList[$tableName])) {
            return null;
        }
        if (!isset($this->indexList[$tableName][$indexName])) {
            return null;
        }
        return $this->indexList[$tableName][$indexName];
    }


}