<?php

namespace siestaphp\generator;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\attribute\AttributeTransformerSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\entity\EntityTransformerSource;
use siestaphp\driver\ColumnMigrator;
use siestaphp\driver\Connection;

/**
 * Class Migrator
 * @package siestaphp\generator
 */
class Migrator
{

    /**
     * @var DataModelContainer
     */
    protected $dataModelContainer;

    /**
     * @var EntitySource[]
     */
    protected $databaseModel;

    /**
     * list of table names that are also defined in the current datamodel
     * @var string[]
     */
    protected $neededTableList;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var ColumnMigrator
     */
    protected $columnMigrator;

    /**
     * @var String[]
     */
    protected $alterStatementList;

    /**
     * @param DataModelContainer $dataModelContainer
     * @param Connection $connection
     */
    public function __construct(DataModelContainer $dataModelContainer, Connection $connection)
    {
        $this->dataModelContainer = $dataModelContainer;
        $this->connection = $connection;
        $this->columnMigrator = $connection->getColumnMigrator();
        $this->databaseModel = array();
        $this->neededTableList = array();
        $this->alterStatementList = array();
    }

    public function migrateModel()
    {

        $this->databaseModel = $this->connection->getEntitySourceList();

        foreach ($this->dataModelContainer->getEntityList() as $entity) {
            $this->migrateEntity($entity);
        }

    }

    /**
     * @param EntityTransformerSource $modelSource
     */
    private function migrateEntity(EntityTransformerSource $modelSource)
    {
        $databaseEntity = $this->getDatabaseEntityByTableName($modelSource->getTable());

        if (!$databaseEntity) {
            $this->createEntity($modelSource);
            return;
        }

        $this->migrateAttributeList($databaseEntity->getAttributeSourceList(), $modelSource->getAttributeSourceList());
    }

    /**
     * @param AttributeSource[] $databaseAttributeList
     * @param AttributeTransformerSource[] $modelAttributeList
     */
    private function migrateAttributeList($databaseAttributeList, $modelAttributeList)
    {
        $processedDatabaseList = array();

        // iterate attributes from model and retrieve alter statements
        foreach ($modelAttributeList as $modelAttribute) {

            // check if a corresponding database attribute exists
            $databaseAttribute = $this->getAttributeSourceByName($databaseAttributeList, $modelAttribute->getName());

            // retrieve alter statements from migrator and add them
            $statementList = $this->columnMigrator->getAttributeAlterStatement($databaseAttribute, $modelAttribute);
            $this->addAlterStatements($statementList);

            // if a database attribute has been found add it to the processed list
            if ($databaseAttribute) {
                $processedDatabaseList[] = $databaseAttribute->getName();
            }
        }

        // iterate attributes from database and retrieve alter statements
        foreach ($databaseAttributeList as $databaseAttribute) {

            // check if attribute has already been processed
            if (in_array($databaseAttribute->getName(), $processedDatabaseList)) {
                continue;
            }

            // no corresponding model attribute will result in drop statements
            $statementList = $this->columnMigrator->getAttributeAlterStatement($databaseAttribute, null);
            $this->addAlterStatements($statementList);
        }
    }

    /**
     * @param AttributeSource[] $attributeSourceList
     * @param string $attributeName
     *
     * @return AttributeSource|null
     */
    private function getAttributeSourceByName($attributeSourceList, $attributeName)
    {
        foreach ($attributeSourceList as $attribute) {
            if ($attribute->getName() === $attributeName) {
                return $attribute;
            }
        }
        return;
    }

    /**
     * @param EntityTransformerSource $source
     */
    private function createEntity(EntityTransformerSource $source)
    {
        $tableBuilder = $this->connection->getTableBuilder();

        $tableBuilder->setupTables($source);
        $tableBuilder->setupStoredProcedures($source);
    }

    /**
     * @param $tableName
     *
     * @return null|EntitySource
     */
    private function getDatabaseEntityByTableName($tableName)
    {
        foreach ($this->databaseModel as $entity) {
            if ($entity->getTable() === $tableName) {
                // store that this table is actually in use
                $this->neededTableList[] = $tableName;
                return $entity;
            }
        }
        return null;
    }

    /**
     * @param array $alterStatementList
     */
    private function addAlterStatements(array $alterStatementList)
    {
        $this->alterStatementList = array_merge($this->alterStatementList, $alterStatementList);
    }

}