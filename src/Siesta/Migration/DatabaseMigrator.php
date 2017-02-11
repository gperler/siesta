<?php
declare(strict_types = 1);

namespace Siesta\Migration;

use Siesta\Database\Connection;
use Siesta\Database\CreateStatementFactory;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Database\StoredProcedureFactory;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class DatabaseMigrator
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var DatabaseMetaData
     */
    protected $databaseMetaData;

    /**
     * @var TableMetaData[]
     */
    protected $databaseTableList;

    /**
     * @var MigrationStatementFactory
     */
    protected $migrationStatementFactory;

    /**
     * @var StoredProcedureFactory
     */
    protected $storedProcedureFactoy;

    /**
     * @var StoredProcedureMigrator
     */
    protected $storedProcedureMigrator;

    /**
     * @var DataModel
     */
    protected $dataModel;

    /**
     * list of table names that are also defined in the current datamodel
     * @var string[]
     */
    protected $neededTableList;

    /**
     * @var string[]
     */
    protected $dropStoredProcedureStatementList;

    /**
     * @var string[]
     */
    protected $createStoredProcedureStatementList;

    /**
     * @var string[]
     */
    protected $alterStatementList;

    /**
     * @param DataModel $dataModel
     * @param Connection $connection
     */
    public function __construct(DataModel $dataModel, Connection $connection)
    {
        $this->dataModel = $dataModel;
        $this->connection = $connection;
        $this->databaseMetaData = $connection->getDatabaseMetaData();

        $this->storedProcedureFactory = $connection->getStoredProcedureFactory();
        $this->storedProcedureMigrator = new StoredProcedureMigrator($this->storedProcedureFactory);

        $this->migrationStatementFactory = $connection->getMigrationStatementFactory();

        $this->neededTableList = [CreateStatementFactory::SEQUENCER_TABLE_NAME];
        $this->dropStoredProcedureStatementList = [];
        $this->alterStatementList = [];
        $this->createStoredProcedureStatementList = [];

    }

    /**
     * @param bool $dropUnusedTables
     */
    public function createAlterStatementList(bool $dropUnusedTables)
    {
        $this->createDropAllProcedureStatementList();

        $factory = $this->connection->getCreateStatementFactory();
        $this->addStatementList($factory->buildSequencer());

        $this->databaseTableList = $this->databaseMetaData->getTableList();

        foreach ($this->dataModel->getEntityList() as $entity) {
            $this->migrateEntity($entity);
        }

        if ($dropUnusedTables) {
            $this->dropUnusedTables();
        }
    }

    /**
     *
     */
    private function createDropAllProcedureStatementList()
    {
        $storedProcedureNameList = $this->databaseMetaData->getStoredProcedureList();
        foreach ($storedProcedureNameList as $storedProcedureName) {
            $this->dropStoredProcedureStatementList[] = $this->storedProcedureFactory->createDropStatementForProcedureName($storedProcedureName);
        }
    }

    /**
     * @param Entity $entity
     *
     * @return void
     */
    private function migrateEntity(Entity $entity)
    {
        $tableMetaData = $this->getTableMetaDataByEntity($entity);

        // if database entity does not exist, create it
        if ($tableMetaData === null) {

            $this->setupEntityInDatabase($entity);
            return;
        }

        // compare database with current model/schema
        $entityMigrator = new TableMigrator($this->migrationStatementFactory, $tableMetaData, $entity);
        $alterStatementList = $entityMigrator->createAlterStatementList();
        $this->addAlterStatementList($alterStatementList);

        $this->checkDelimit($entity);
        $this->checkReplication($entity);

        // create stored procedures
        $statementList = $this->storedProcedureMigrator->getMigrateProcedureStatementList($this->dataModel, $entity);
        $this->addStatementList($statementList);
    }

    /**
     * @param Entity $entity
     */
    protected function checkDelimit(Entity $entity)
    {
        if (!$entity->getIsDelimit()) {
            return;
        }
        foreach ($this->databaseTableList as $tableMetaData) {
            if ($tableMetaData->getName() === $entity->getDelimitTableName()) {
                return;
            }
        }
        $factory = $this->connection->getCreateStatementFactory();
        $this->addAlterStatementList($factory->buildCreateDelimitTable($entity));
    }

    /**
     * @param Entity $entity
     */
    protected function checkReplication(Entity $entity)
    {
        if (!$entity->getIsReplication()) {
            return;
        }
        foreach ($this->databaseTableList as $tableMetaData) {
            if ($tableMetaData->getName() === $entity->getReplicationTableName()) {
                return;
            }
        }
        $factory = $this->connection->getCreateStatementFactory();
        $this->addAlterStatementList($factory->buildCreateTable($entity));
    }

    /**
     * @param Entity $entity
     *
     * @return void
     */
    private function setupEntityInDatabase(Entity $entity)
    {
        $factory = $this->connection->getCreateStatementFactory();

        $this->addAlterStatementList($factory->buildCreateTable($entity));

        if ($entity->getIsDelimit()) {
            $this->addAlterStatementList($factory->buildCreateDelimitTable($entity));
        }

        $spMigrationList = $this->storedProcedureMigrator->getMigrateProcedureStatementList($this->dataModel, $entity);
        $this->addStatementList($spMigrationList);
    }

    /**
     * @param Entity $entity
     *
     * @return TableMetaData|null
     */
    private function getTableMetaDataByEntity(Entity $entity)
    {
        $tableName = $entity->getTableName();

        foreach ($this->databaseTableList as $tableMetaData) {
            if ($tableMetaData->getName() === $tableName) {

                $this->neededTableList[] = $tableName;

                if ($entity->getIsDelimit()) {
                    $this->neededTableList[] = $entity->getDelimitTableName();
                }

                if ($entity->getIsReplication()) {
                    $this->neededTableList[] = $entity->getReplicationTableName();
                }

                return $tableMetaData;
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
        foreach ($this->databaseTableList as $databaseTable) {
            if (in_array($databaseTable->getName(), $this->neededTableList)) {
                continue;
            }
            $statementList = $this->migrationStatementFactory->getDropTableStatement($databaseTable);

            foreach ($statementList as $statement) {
                $dropStatement = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $databaseTable->getName(), $statement);
                $this->addAlterStatementList([$dropStatement]);
            }
        }
    }

    /**
     * @param array $statementList
     *
     * @return void
     */
    private function addStatementList(array $statementList)
    {
        $this->createStoredProcedureStatementList = array_merge($this->createStoredProcedureStatementList, $statementList);
    }

    /**
     * @param array $alterStatementList
     */
    private function addAlterStatementList(array $alterStatementList)
    {
        $this->alterStatementList = array_merge($this->alterStatementList, $alterStatementList);
    }

    /**
     * @return string[]
     */
    public function getDropStoredProcedureStatementList()
    {
        return $this->dropStoredProcedureStatementList;
    }

    /**
     * will contain create table and alter table statements
     * @return String[]
     */
    public function getAlterStatementList()
    {
        return $this->alterStatementList;
    }

    /**
     * will contain stored procedure create and drop statements as well as sequencer
     * @return String[]
     */
    public function getCreateStoredProcedureStatementList()
    {
        return $this->createStoredProcedureStatementList;
    }



}