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
    protected $statementList;

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
        $this->databaseModel = [];
        $this->neededTableList = [CreateStatementFactory::SEQUENCER_TABLE_NAME];
        $this->alterStatementList = [];
        $this->statementList = [];
    }

    /**
     * @param string $targetNamespace
     * @param string $targetPath
     * @param bool $dropUnusedTables
     */
    public function createAlterStatementList($targetNamespace, $targetPath, $dropUnusedTables)
    {
        $factory = $this->connection->getCreateStatementFactory();
        $this->addStatementList($factory->buildSequencer());

        // retrieve entity list as currently setup in the database
        $this->databaseModel = $this->connection->getEntitySourceList($targetNamespace, $targetPath);

        // iterate database entities and migrate entity
        foreach ($this->dataModelContainer->getEntityList() as $entity) {
            $this->migrateEntity($entity);
        }

        // check if unused tables shall be dropped
        if ($dropUnusedTables) {
            $this->dropUnusedTables();
        }
    }

    /**
     * @param EntityGeneratorSource $modelSource
     *
     * @return void
     */
    private function migrateEntity(EntityGeneratorSource $modelSource)
    {
        $databaseEntity = $this->getDatabaseEntityByModelSource($modelSource);

        // if database entity does not exist, create it
        if ($databaseEntity === null) {

            $this->setupEntityInDatabase($modelSource);
            return;
        }

        // compare database with current model/schema
        $entityMigrator = new EntityMigrator($this->columnMigrator, $databaseEntity, $modelSource);
        $alterStatementList = $entityMigrator->createAlterStatementList();

        $this->addAlterStatementList($alterStatementList);

        // create stored procedures
        $factory = $this->connection->getCreateStatementFactory();
        $this->addStatementList($factory->buildStoredProceduresStatements($modelSource));

    }

    /**
     * @param EntityGeneratorSource $source
     *
     * @return void
     */
    private function setupEntityInDatabase(EntityGeneratorSource $source)
    {
        $factory = $this->connection->getCreateStatementFactory();

        $this->addAlterStatementList($factory->buildCreateTable($source));

        $this->addStatementList($factory->buildStoredProceduresStatements($source));

        if ($source->isDelimit()) {
            $this->addAlterStatementList($factory->buildCreateDelimitTable($source));
        }
    }

    /**
     * @param EntityGeneratorSource $entityGeneratorSource
     *
     * @return EntitySource|null
     */
    private function getDatabaseEntityByModelSource(EntityGeneratorSource $entityGeneratorSource)
    {
        $tableName = $entityGeneratorSource->getTable();
        foreach ($this->databaseModel as $entity) {
            if ($entity->getTable() === $tableName) {
                // store that this table is actually in use
                $this->neededTableList[] = $tableName;

                if ($entityGeneratorSource->isDelimit()) {
                    $this->neededTableList[] = $entityGeneratorSource->getDelimitTable();
                }

                // TODO: handle replication

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
            $statement = $this->columnMigrator->getDropTableStatement($databaseModel);
            $dropStatement = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $databaseModel->getTable(), $statement);
            $this->addAlterStatementList([$dropStatement]);
        }
    }

    /**
     * @param array $statementList
     *
     * @return void
     */
    private function addStatementList(array $statementList)
    {
        $this->statementList = array_merge($this->statementList, $statementList);
    }

    /**
     * @param array $alterStatementList
     */
    private function addAlterStatementList(array $alterStatementList)
    {
        $this->alterStatementList = array_merge($this->alterStatementList, $alterStatementList);
    }

    /**
     * @return String[]
     */
    public function getStatementList()
    {
        return $this->statementList;
    }

    /**
     * @return String[]
     */
    public function getAlterStatementList()
    {
        return $this->alterStatementList;
    }

}