<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;
use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Driver\MySQL\MySQLDriver;
use Siesta\Driver\MySQL\MySQLTableCreator;

/**
 * @author Gregor Müller
 */
class MySQLTable implements TableMetaData
{

    const SQL_GET_COLUMN_DETAILS = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s';";

    const SQL_GET_KEY_COLUMN_USAGE = "SELECT * FROM information_schema.key_column_usage as KC WHERE KC.CONSTRAINT_SCHEMA = '%s' AND KC.TABLE_NAME = '%s'";

    const SQL_GET_REFERENTIAL_CONSTRAINTS = "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS AS RC WHERE RC.CONSTRAINT_SCHEMA = '%s' AND RC.TABLE_NAME = '%s'";

    const SQL_GET_INDEX_LIST = "SELECT S.* FROM INFORMATION_SCHEMA.STATISTICS AS S WHERE S.TABLE_SCHEMA = '%s' AND S.TABLE_NAME = '%s';";

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var TableDTO
     */
    protected $tableDTO;

    /**
     * @var MySQLColumn[]
     */
    protected $columnList;

    /**
     * @var MySQLConstraint[]
     */
    protected $constraintList;

    /**
     * @var MySQLIndex[]
     */
    protected $indexList;

    /**
     * MySQLTable constructor.
     *
     * @param Connection $connection
     * @param TableDTO $tableDTO
     */
    public function __construct(Connection $connection, TableDTO $tableDTO)
    {
        $this->connection = $connection;
        $this->tableDTO = $tableDTO;
        $this->columnList = [];
        $this->constraintList = [];
        $this->indexList = [];
        $this->extractColumnData();
        $this->extractReferenceData();
        $this->extractIndexData();
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->tableDTO->name;
    }

    /**
     * @return ColumnMetaData[]
     */
    public function getColumnList() : array
    {
        return $this->columnList;
    }

    /**
     * @param string $name
     *
     * @return ColumnMetaData|null
     */
    public function getColumnByName(string $name)
    {
        foreach ($this->columnList as $column) {
            if ($column->getDBName() === $name) {
                return $column;
            }
        }
        return null;
    }

    /**
     * @return ConstraintMetaData[]
     */
    public function getConstraintList() : array
    {
        return $this->constraintList;
    }

    /**
     * @return IndexMetaData[]
     */
    public function getIndexList() : array
    {
        return $this->indexList;
    }

    /**
     * @return ColumnMetaData[]
     */
    public function getPrimaryKeyAttributeList() : array
    {
        $primaryKeyColumnList = [];
        foreach ($this->getColumnList() as $column) {
            if ($column->getIsPrimaryKey()) {
                $primaryKeyColumnList[] = $column;
            }
        }
        return $primaryKeyColumnList;
    }

    /**
     * @param string $name
     *
     * @return null|MySQLConstraint
     */
    public function getConstraintByName(string $name)
    {
        foreach ($this->constraintList as $constraint) {
            if ($constraint->getConstraintName() === $name) {
                return $constraint;
            }
        }
        return null;
    }

    /**
     * @param string $indexName
     *
     * @return null|MySQLIndex
     */
    public function getIndexByName(string $indexName)
    {
        foreach ($this->indexList as $index) {
            if ($index->getName() === $indexName) {
                return $index;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getDataBaseSpecific() : array
    {
        return [
            MySQLDriver::MYSQL_DRIVER_NAME => [
                MySQLTableCreator::MYSQL_ENGINE_ATTRIBUTE => $this->tableDTO->engine,
                MySQLTableCreator::MYSQL_COLLATE_ATTRIBUTE => $this->tableDTO->collation
            ]
        ];
    }

    /**
     *
     */
    protected function extractColumnData()
    {

        $sql = sprintf(self::SQL_GET_COLUMN_DETAILS, $this->connection->getDatabase(), $this->getName());

        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $column = new MySQLColumn();
            $column->fromResultSet($resultSet, $this->tableDTO->autoincrement);
            $this->columnList[] = $column;
        }

        $resultSet->close();
    }

    /**
     *
     */
    protected function extractReferenceData()
    {
        $sql = sprintf(self::SQL_GET_KEY_COLUMN_USAGE, $this->connection->getDatabase(), $this->getName());

        $resultSet = $this->connection->query($sql);

        while ($resultSet->hasNext()) {

            // skip primary key index
            if ($resultSet->getStringValue(MySQLConstraint::REFERENCED_TABLE_NAME) === null) {
                continue;
            }

            $constraintName = $resultSet->getStringValue(MySQLConstraint::CONSTRAINT_NAME);
            $constraint = $this->getConstraintByName($constraintName);

            if ($constraint === null) {
                $constraint = new MySQLConstraint($resultSet);
                $constraint->fromResultSet($resultSet);
                $this->constraintList[] = $constraint;
            } else {
                $constraint->addConstraint($resultSet);
            }

        }
        $resultSet->close();

        $sql = sprintf(self::SQL_GET_REFERENTIAL_CONSTRAINTS, $this->connection->getDatabase(), $this->getName());
        $resultSet = $this->connection->query($sql);

        while ($resultSet->hasNext()) {
            $constraintName = $resultSet->getStringValue(MySQLConstraint::CONSTRAINT_NAME);
            $constraint = $this->getConstraintByName($constraintName);

            if ($constraint !== null) {
                $constraint->addUpdateAndDeleteRule($resultSet);
            }

        }
        $resultSet->close();

    }

    /**
     * extracts index data
     */
    protected function extractIndexData()
    {
        $sql = sprintf(self::SQL_GET_INDEX_LIST, $this->connection->getDatabase(), $this->getName());

        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {

            $indexName = $resultSet->getStringValue(MySQLIndex::INDEX_NAME);
            if ($indexName === MySQLIndex::PRIMARY_KEY_INDEX_NAME) {
                continue;
            }

            $constraint = $this->getConstraintByName($indexName);
            if ($constraint !== null) {
                continue;
            }

            $index = $this->getIndexByName($indexName);
            if ($index === null) {
                $index = new MySQLIndex();
                $index->fromResultSet($resultSet);
                $this->indexList[] = $index;
            } else {
                $index->addIndexPart($resultSet);
            }
        }
    }

}