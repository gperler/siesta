<?php

declare(strict_types=1);

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
    protected Connection $connection;

    /**
     * @var DatabaseMetaData
     */
    protected DatabaseMetaData $databaseMetaData;

    /**
     * @var TableMetaData[]
     */
    protected array $databaseTableList;

    /**
     * @var MigrationStatementFactory
     */
    protected MigrationStatementFactory $migrationStatementFactory;

    /**
     * @var StoredProcedureFactory
     */
    protected StoredProcedureFactory $storedProcedureFactory;

    /**
     * @var StoredProcedureMigrator
     */
    protected StoredProcedureMigrator $storedProcedureMigrator;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * list of table names that are also defined in the current dataModel
     *
     * @var string[]
     */
    protected array $neededTableList;

    /**
     * @var string[]
     */
    protected array $alterStatementList;


    /**
     * @param DataModel $dataModel
     * @param Connection $connection
     */
    public function __construct(DataModel $dataModel, Connection $connection)
    {
        $this->dataModel = $dataModel;
        $this->connection = $connection;
        $this->alterStatementList = [];
        $this->databaseMetaData = $connection->getDatabaseMetaData();
        $this->storedProcedureFactory = $connection->getStoredProcedureFactory();
        $this->migrationStatementFactory = $connection->getMigrationStatementFactory();
        $this->databaseTableList = $this->databaseMetaData->getTableList();

        $this->createStoredProcedureMigrator();
        $this->checkSequencerTable();
    }


    /**
     *
     */
    private function checkSequencerTable(): void
    {
        $this->neededTableList = [CreateStatementFactory::SEQUENCER_TABLE_NAME];
        $tableMetaData = $this->getTableMetaDataByName(CreateStatementFactory::SEQUENCER_TABLE_NAME);

        if ($tableMetaData !== null) {
            return;
        }

        $createStatementFactory = $this->connection->getCreateStatementFactory();


        $sequencerTableCreateStatement = $createStatementFactory->buildSequencerTable();

        $this->addAlterStatementList([$sequencerTableCreateStatement]);
    }


    /**
     *
     */
    private function createStoredProcedureMigrator(): void
    {
        $activeStoredProcedureList = $this->databaseMetaData->getStoredProcedureList();
        $createStatementFactory = $this->connection->getCreateStatementFactory();
        $sequencerStoredProcedure = $createStatementFactory->buildSequencerStoredProcedure();
        $this->storedProcedureMigrator = new StoredProcedureMigrator($this->storedProcedureFactory, $activeStoredProcedureList, $sequencerStoredProcedure);
    }


    /**
     * @param bool $dropUnusedTables
     */
    public function createAlterStatementList(bool $dropUnusedTables): void
    {
        foreach ($this->dataModel->getEntityList() as $entity) {
            $this->migrateEntity($entity);
        }

        if ($dropUnusedTables) {
            $this->dropUnusedTables();
        }
    }


    /**
     * @param Entity $entity
     *
     * @return void
     */
    private function migrateEntity(Entity $entity): void
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
        $this->storedProcedureMigrator->createProcedureStatementList($this->dataModel, $entity);
    }


    /**
     * @param Entity $entity
     */
    protected function checkDelimit(Entity $entity): void
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
    protected function checkReplication(Entity $entity): void
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
    private function setupEntityInDatabase(Entity $entity): void
    {
        $factory = $this->connection->getCreateStatementFactory();

        $this->addAlterStatementList($factory->buildCreateTable($entity));

        if ($entity->getIsDelimit()) {
            $this->addAlterStatementList($factory->buildCreateDelimitTable($entity));
        }

        $this->storedProcedureMigrator->createProcedureStatementList($this->dataModel, $entity);
    }


    /**
     * @param Entity $entity
     *
     * @return TableMetaData|null
     */
    private function getTableMetaDataByEntity(Entity $entity): ?TableMetaData
    {
        $tableName = $entity->getTableName();
        $tableMetaData = $this->getTableMetaDataByName($tableName);
        if ($tableMetaData === null) {
            return null;
        }

        $this->neededTableList[] = $tableName;

        if ($entity->getIsDelimit()) {
            $this->neededTableList[] = $entity->getDelimitTableName();
        }

        if ($entity->getIsReplication()) {
            $this->neededTableList[] = $entity->getReplicationTableName();
        }

        return $tableMetaData;
    }


    /**
     * @param string $tableName
     *
     * @return null|TableMetaData
     */
    private function getTableMetaDataByName(string $tableName): ?TableMetaData
    {
        return $this->databaseTableList[$tableName] ?? null;
    }


    /**
     * drops unused database tables
     *
     * @return void
     */
    private function dropUnusedTables(): void
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
     * @param array $alterStatementList
     */
    private function addAlterStatementList(array $alterStatementList): void
    {
        $this->alterStatementList = array_merge($this->alterStatementList, $alterStatementList);
    }


    /**
     * will contain create table and alter table statements
     *
     * @return String[]
     */
    public function getAlterStatementList(): array
    {
        return $this->alterStatementList;
    }


    /**
     * will contain stored procedure create and drop statements as well as sequencer
     *
     * @return String[]
     */
    public function getAlterStoredProcedureStatementList(): array
    {
        return $this->storedProcedureMigrator->getStoredProcedureMigrationList();
    }


}