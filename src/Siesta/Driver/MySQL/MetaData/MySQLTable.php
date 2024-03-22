<?php

declare(strict_types=1);

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


    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @var TableDTO
     */
    protected TableDTO $tableDTO;

    /**
     * @var MySQLColumn[]
     */
    protected array $columnList;

    /**
     * @var MySQLConstraint[]
     */
    protected array $constraintList;

    /**
     * @var MySQLIndex[]
     */
    protected array $indexList;


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
    }


    /**
     * @return bool|null
     */
    public function getAutoincrement(): ?bool
    {
        return $this->tableDTO->autoincrement;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->tableDTO->name;
    }


    /**
     * @param MySQLColumn[] $columnList
     */
    public function setColumnList(array $columnList): void
    {
        $this->columnList = $columnList;
    }


    /**
     * @return ColumnMetaData[]
     */
    public function getColumnList(): array
    {
        return $this->columnList;
    }


    /**
     * @param string $name
     *
     * @return ColumnMetaData|null
     */
    public function getColumnByName(string $name): ?ColumnMetaData
    {
        return $this->columnList[$name] ?? null;
    }


    /**
     * @param MySQLConstraint[] $constraintList
     */
    public function setConstraintList(array $constraintList): void
    {
        $this->constraintList = $constraintList;
    }


    /**
     * @return ConstraintMetaData[]
     */
    public function getConstraintList(): array
    {
        return $this->constraintList;
    }


    /**
     * @param string $name
     *
     * @return null|MySQLConstraint
     */
    public function getConstraintByName(string $name): ?ConstraintMetaData
    {
        return $this->constraintList[$name] ?? null;
    }


    /**
     * @param MySQLIndex[] $indexList
     */
    public function setIndexList(array $indexList): void
    {
        $this->indexList = $indexList;
    }


    /**
     * @return IndexMetaData[]
     */
    public function getIndexList(): array
    {
        return $this->indexList;
    }


    /**
     * @param string $indexName
     *
     * @return null|MySQLIndex
     */
    public function getIndexByName(string $indexName): ?IndexMetaData
    {
        foreach ($this->indexList as $index) {
            if ($index->getName() === $indexName) {
                return $index;
            }
        }
        return null;
    }


    /**
     * @return ColumnMetaData[]
     */
    public function getPrimaryKeyAttributeList(): array
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
     * @return array
     */
    public function getDataBaseSpecific(): array
    {
        return [
            MySQLDriver::MYSQL_DRIVER_NAME => [
                MySQLTableCreator::MYSQL_ENGINE_ATTRIBUTE => $this->tableDTO->engine,
                MySQLTableCreator::MYSQL_COLLATE_ATTRIBUTE => $this->tableDTO->collation
            ]
        ];
    }


}