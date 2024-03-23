<?php

declare(strict_types=1);

namespace Siesta\Migration;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\Attribute;

/**
 * @author Gregor Müller
 */
class AttributeListMigrator
{

    /**
     * @var ColumnMetaData[]
     */
    protected array $columnList;

    /**
     * @var Attribute[]
     */
    protected array $attributeList;

    /**
     * @var MigrationStatementFactory
     */
    protected MigrationStatementFactory $migrationStatementFactory;

    /**
     * @var string[]
     */
    protected array $addStatementList;

    /**
     * @var string[]
     */
    protected array $modifyStatementList;

    /**
     * @var string[]
     */
    protected array $dropStatementList;


    /**
     * AttributeListMigrator constructor.
     *
     * @param MigrationStatementFactory $migrationStatementFactory
     * @param ColumnMetaData[] $columnList
     * @param Attribute[] $attributeList
     */
    public function __construct(MigrationStatementFactory $migrationStatementFactory, array $columnList, array $attributeList)
    {
        $this->migrationStatementFactory = $migrationStatementFactory;
        $this->columnList = $columnList;
        $this->attributeList = $attributeList;
        $this->addStatementList = [];
        $this->modifyStatementList = [];
        $this->dropStatementList = [];
    }


    /**
     * compares attribute list and column list and request the needed alter statements
     *
     * @return void
     */
    public function createAlterStatementList(): void
    {
        $processedDatabaseList = [];

        // iterate attributes from model and retrieve alter statements
        foreach ($this->attributeList as $attribute) {
            // check if a corresponding database attribute exists
            $column = $this->getColumnByAttribute($attribute);

            // retrieve alter statements from migrator and add them
            $this->createAlterStatement($column, $attribute);

            // if a database attribute has been found add it to the processed list
            if ($column) {
                $processedDatabaseList[] = $column->getDBName();
            }
        }

        // iterate attributes from database and retrieve alter statements
        foreach ($this->columnList as $column) {
            // check if attribute has already been processed
            if (in_array($column->getDBName(), $processedDatabaseList)) {
                continue;
            }
            // no corresponding model attribute will result in drop statements
            $this->createAlterStatement($column, null);
        }
    }


    /**
     * @param Attribute $attribute
     *
     * @return null|ColumnMetaData
     */
    protected function getColumnByAttribute(Attribute $attribute): ?ColumnMetaData
    {
        $databaseName = $attribute->getDBName();
        return $this->columnList[$databaseName] ?? null;
    }


    /**
     * @param ColumnMetaData|null $column
     * @param Attribute|null $attribute
     */
    protected function createAlterStatement(ColumnMetaData $column = null, Attribute $attribute = null): void
    {
        // no column create it
        if ($column === null) {
            if ($attribute !== null and $attribute->getIsTransient()) {
                return;
            }
            $addList = $this->migrationStatementFactory->createAddColumnStatement($attribute);
            $this->addAddStatementList($addList);
            return;
        }

        // no attribute drop the column
        if ($attribute === null or $attribute->getIsTransient()) {
            $dropList = $this->migrationStatementFactory->createDropColumnStatement($column);
            $this->addDropStatementList($dropList);
            return;
        }

        // types identical nothing to do
        if ($this->areIdentical($attribute, $column)) {
            return;
        }

        // types not identical
        $modifyList = $this->migrationStatementFactory->createModifyColumnStatement($attribute);
        $this->addModifyStatementList($modifyList);
    }


    /**
     * @param Attribute $attribute
     * @param ColumnMetaData $column
     *
     * @return bool
     */
    protected function areIdentical(Attribute $attribute, ColumnMetaData $column): bool
    {
        if ($column->getIsRequired() !== $attribute->getIsRequired()) {
            return false;
        }
        $attributeType = strtoupper($attribute->getDbType());
        $columnType = strtoupper($column->getDBType());

        return $attributeType === $columnType;
    }


    /**
     * @param array $statementList
     */
    protected function addModifyStatementList(array $statementList): void
    {
        $this->modifyStatementList = array_merge($this->modifyStatementList, $statementList);
    }


    /**
     * @param array $statementList
     */
    protected function addAddStatementList(array $statementList): void
    {
        $this->addStatementList = array_merge($this->addStatementList, $statementList);
    }


    /**
     * @param array $statementList
     */
    protected function addDropStatementList(array $statementList): void
    {
        $this->dropStatementList = array_merge($this->dropStatementList, $statementList);
    }


    /**
     * @return string[]
     */
    public function getAddColumnStatementList(): array
    {
        return $this->addStatementList;
    }


    /**
     * @return string[]
     */
    public function getModifyColumnStatementList(): array
    {
        return $this->modifyStatementList;
    }


    /**
     * @return string[]
     */
    public function getDropColumnStatementList(): array
    {
        return $this->dropStatementList;
    }
}