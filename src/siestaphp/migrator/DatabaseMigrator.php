<?php

namespace siestaphp\migrator;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ColumnMigrator;
use siestaphp\driver\Connection;

/**
 * Class DatabaseMigrator
 * @package siestaphp\generator
 */
class DatabaseMigrator
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

    /**
     * @param bool $dropUnusedTables
     *
     * @return string[]
     */
    public function createAlterStatementList($dropUnusedTables = false)
    {

        $this->databaseModel = $this->connection->getEntitySourceList();

        foreach ($this->dataModelContainer->getEntityList() as $entity) {
            $this->migrateEntity($entity);
        }

        if ($dropUnusedTables) {
            $this->dropUnusedTables();
        }

        return $this->alterStatementList;

    }

    /**
     * @param EntityGeneratorSource $modelSource
     *
     * @return void
     */
    private function migrateEntity(EntityGeneratorSource $modelSource)
    {
        $databaseEntity = $this->getDatabaseEntityByTableName($modelSource->getTable());

        if ($databaseEntity === null) {
            $this->createEntity($modelSource);
            return;
        }
        $entityMigrator = new EntityMigrator($this->columnMigrator, $databaseEntity, $modelSource);
        $statementList = $entityMigrator->createAlterStatementList();
        $this->addAlterStatements($statementList);
    }

    /**
     * @param EntityGeneratorSource $source
     *
     * @return void
     */
    private function createEntity(EntityGeneratorSource $source)
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
     * drops unused database tables
     * @return void
     */
    private function dropUnusedTables()
    {
        foreach ($this->databaseModel as $databaseModel) {
            if (in_array($databaseModel->getTable(), $this->neededTableList)) {
                continue;
            }
            $this->alterStatementList[] = $this->columnMigrator->getDropTableStatement($databaseModel);
        }
    }

    /**
     * @param array $alterStatementList
     *
     * @return void
     */
    private function addAlterStatements(array $alterStatementList)
    {
        $this->alterStatementList = array_merge($this->alterStatementList, $alterStatementList);
    }

}