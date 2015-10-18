<?php

namespace siestaphp\migrator;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ColumnMigrator;
use siestaphp\driver\Connection;
use siestaphp\driver\CreateStatementFactory;

/**
 * Class DatabaseMigrator allows to retrieve alter statement by comparing model/schema with current database setup
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
        $this->neededTableList = array( CreateStatementFactory::SEQUENCER_TABLE_NAME );
        $this->alterStatementList = array();
    }

    /**
     * @param bool $dropUnusedTables
     *
     * @return string[]
     */
    public function createAlterStatementList($dropUnusedTables = false)
    {
        $factory = $this->connection->getCreateStatementFactory();
        $this->addAlterStatements($factory->setupSequencer());

        // retrieve entity list as currently setup in the database
        $this->databaseModel = $this->connection->getEntitySourceList();

        // iterate database entities and migrate entity
        foreach ($this->dataModelContainer->getEntityList() as $entity) {
            $this->migrateEntity($entity);
        }

        // check if unused tables shall be dropped
        if ($dropUnusedTables) {
            $this->dropUnusedTables();
        }

        // done, return list of needed statements
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

        // if database entity does not exist, create it
        if ($databaseEntity === null) {
            $this->setupEntityInDatabase($modelSource);
            return;
        }

        // compare database with current model/schema
        $entityMigrator = new EntityMigrator($this->columnMigrator, $databaseEntity, $modelSource);
        $statementList = $entityMigrator->createAlterStatementList();
        $this->addAlterStatements($statementList);

        // create stored procedures
        $tableBuilder = $this->connection->getCreateStatementFactory();
        $tableBuilder->setupStoredProcedures($modelSource);

    }

    /**
     * @param EntityGeneratorSource $source
     *
     * @return void
     */
    private function setupEntityInDatabase(EntityGeneratorSource $source)
    {
        $factory = $this->connection->getCreateStatementFactory();

        $this->addAlterStatements($factory->setupTables($source));
        $this->addAlterStatements($factory->setupStoredProcedures($source));

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