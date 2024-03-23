<?php
declare(strict_types=1);

namespace Siesta\Migration;

use Siesta\Database\MetaData\TableMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class TableMigrator
{

    /**
     * @var MigrationStatementFactory
     */
    protected MigrationStatementFactory $migrationStatementFactory;

    /**
     * @var TableMetaData
     */
    protected TableMetaData $tableMetaData;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var AttributeListMigrator
     */
    protected AttributeListMigrator $attributeListMigrator;

    /**
     * @var ReferenceListMigrator
     */
    protected ReferenceListMigrator $referenceListMigrator;

    /**
     * @var IndexListMigrator
     */
    protected IndexListMigrator $indexListMigrator;

    /**
     * @var string[]
     */
    protected array $alterStatementList;

    /**
     * TableMigrator constructor.
     *
     * @param MigrationStatementFactory $migrationStatementFactory
     * @param TableMetaData $tableMetaData
     * @param Entity $entity
     */
    public function __construct(MigrationStatementFactory $migrationStatementFactory, TableMetaData $tableMetaData, Entity $entity)
    {
        $this->migrationStatementFactory = $migrationStatementFactory;
        $this->tableMetaData = $tableMetaData;
        $this->entity = $entity;
        $this->alterStatementList = [];
    }

    /**
     * returns the list of ALTER statement for the migration of the given database entity to the target model entity
     * @return string[]
     */
    public function createAlterStatementList(): array
    {
        $this->migrateAttributeList();
        $this->migrateReferenceList();
        $this->migrateIndexList();
        $this->assembleStatementList();

        return $this->alterStatementList;
    }

    /**
     * brings the alter table statements into the right order. First indexes and foreign keys are dropped, then columns
     * are added, existing ones are modified. Then the primary key is changed (if needed). Afterwards not needed
     * columns are dropped and finally indexes and foreign key constraints are added.
     * @return void
     */
    private function assembleStatementList(): void
    {

        // drop foreign key and index
        $this->addStatementList($this->referenceListMigrator->getDropForeignKeyStatementList());
        $this->addStatementList($this->indexListMigrator->getDropIndexStatementList());

        // add columns
        $this->addStatementList($this->attributeListMigrator->getAddColumnStatementList(), true);
        $this->addStatementList($this->attributeListMigrator->getModifyColumnStatementList(), true);

        // modify primary key
        $this->addStatementList($this->getMigratePrimaryKeyStatementList());

        // drop columns
        $this->addStatementList($this->attributeListMigrator->getDropColumnStatementList(), true);

        // add foreign key and index
        $this->addStatementList($this->referenceListMigrator->getAddForeignKeyStatementList());
        $this->addStatementList($this->indexListMigrator->getAddIndexStatementList());

    }

    /**
     * compares the primary key column and generates migrate primary key statements if needed
     * @return array
     */
    private function getMigratePrimaryKeyStatementList(): array
    {
        $tablePrimaryKeyList = $this->getTablePrimaryKeyList($this->tableMetaData);
        $entityPrimaryKeyList = $this->getEntityPrimaryKeyColumnList($this->entity);

        $nonEntityPK = array_diff($tablePrimaryKeyList, $entityPrimaryKeyList);
        $nonTablePK = array_diff($entityPrimaryKeyList, $tablePrimaryKeyList);

        if (sizeof($nonEntityPK) === 0 && sizeof($nonTablePK) === 0) {
            return [];
        }

        return $this->migrationStatementFactory->getModifyPrimaryKeyStatement($this->tableMetaData, $this->entity);
    }

    /**
     * @param Entity $entity
     *
     * @return array
     */
    protected function getEntityPrimaryKeyColumnList(Entity $entity): array
    {
        $pkList = [];
        foreach ($entity->getPrimaryKeyAttributeList() as $attribute) {
            $pkList[] = $attribute->getDBName();
        }

        return $pkList;
    }

    /**
     * @param TableMetaData $tableMetaData
     *
     * @return array
     */
    protected function getTablePrimaryKeyList(TableMetaData $tableMetaData): array
    {
        $pkList = [];
        foreach ($tableMetaData->getPrimaryKeyAttributeList() as $columnMetaData) {
            $pkList[] = $columnMetaData->getDBName();
        }
        return $pkList;
    }

    /**
     * migrates the attributes of the entity and gathers add modify and drop statements
     * @return void
     */
    private function migrateAttributeList(): void
    {
        $this->attributeListMigrator = new AttributeListMigrator($this->migrationStatementFactory, $this->tableMetaData->getColumnList(), $this->entity->getAttributeList());
        $this->attributeListMigrator->createAlterStatementList();

    }

    /**
     * migrates the references of the entity and gathers add modify and drop statements for the used columns and add
     * drop constraints statements
     * @return void
     */
    private function migrateReferenceList(): void
    {
        $this->referenceListMigrator = new ReferenceListMigrator($this->migrationStatementFactory, $this->tableMetaData->getConstraintList(), $this->entity->getReferenceList());
        $this->referenceListMigrator->createAlterStatementList();
    }

    /**
     * migrates the indexes of the entity and gathers drop and add statements
     * @return void
     */
    private function migrateIndexList(): void
    {
        $this->indexListMigrator = new IndexListMigrator($this->migrationStatementFactory, $this->tableMetaData->getIndexList(), $this->entity->getIndexList());
        $this->indexListMigrator->createAlterStatementList();
    }

    /**
     * adds a statement and replaces the table place holder against the real table. Considers delimited tables.
     *
     * @param string[] $statementList
     * @param bool $isAttribute delimiter tables are only changed for the attributes
     *
     * @return void
     */
    private function addStatementList(array $statementList, bool $isAttribute = false): void
    {
        foreach ($statementList as $statement) {
            $this->alterStatementList[] = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->tableMetaData->getName(), $statement);

            // handle delimited table
            if ($this->entity->getIsDelimit() and $isAttribute) {
                $this->alterStatementList[] = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->entity->getDelimitTableName(), $statement);
            }

            if ($this->entity->getIsReplication()) {
                $this->alterStatementList[] = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->entity->getReplicationTableName(), $statement);
            }
        }
    }

}