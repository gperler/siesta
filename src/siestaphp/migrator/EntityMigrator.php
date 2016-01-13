<?php

namespace siestaphp\migrator;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ColumnMigrator;

/**
 * Class EntityMigrator
 * @package siestaphp\migrator
 */
class EntityMigrator
{

    /**
     * @var ColumnMigrator
     */
    protected $columnMigrator;

    /**
     * @var EntitySource
     */
    protected $databaseEntity;

    /**
     * @var EntityGeneratorSource
     */
    protected $modelEntity;

    /**
     * @var AttributeListMigrator
     */
    protected $attributeListMigrator;

    /**
     * @var ReferenceListMigrator
     */
    protected $referenceListMigrator;

    /**
     * @var IndexListMigrator
     */
    protected $indexListMigrator;

    /**
     * @var string[]
     */
    protected $statementList;

    /**
     * creates a new entity migrator.
     *
     * @param ColumnMigrator $migrator           interface to the database specific migrator
     * @param EntitySource $databaseEntity       current as is entity that needs to be transformed
     * @param EntityGeneratorSource $modelEntity target state the is supposed to be created
     */
    public function __construct(ColumnMigrator $migrator, EntitySource $databaseEntity, EntityGeneratorSource $modelEntity)
    {
        $this->columnMigrator = $migrator;
        $this->databaseEntity = $databaseEntity;
        $this->modelEntity = $modelEntity;
        $this->statementList = [];
    }

    /**
     * returns the list of ALTER statement for the migration of the given database entity to the target model entity
     * @return string[]
     */
    public function createAlterStatementList()
    {
        $this->migrateAttributeList();
        $this->migrateReferenceList();
        $this->migrateIndexList();
        $this->assembleStatementList();

        return $this->statementList;
    }

    /**
     * brings the alter table statements into the right order. First indexes and foreign keys are dropped, then columns
     * are added, existing ones are modified. Then the primary key is changed (if needed). Afterwards not needed
     * columns are droped and finaly indexes and foreign key constraints are addedd.
     * @return void
     */
    private function assembleStatementList()
    {

        // drop foreign key and index
        $this->addStatementList($this->referenceListMigrator->getDropForeignKeyStatementList());
        $this->addStatementList($this->indexListMigrator->getDropIndexStatementList());

        // add columns
        $this->addStatementList($this->attributeListMigrator->getAddColumnStatementList(), true);
        $this->addStatementList($this->attributeListMigrator->getModifyColumnStatementList(), true);

        // modify columns
        $this->addStatementList($this->referenceListMigrator->getAddColumnStatementList());
        $this->addStatementList($this->referenceListMigrator->getModifyColumnStatementList());

        // modify primary key
        $this->addStatementList($this->getMigratePrimaryKeyStatementList());

        // drop columns
        $this->addStatementList($this->attributeListMigrator->getDropColumnStatementList(), true);
        $this->addStatementList($this->referenceListMigrator->getDropColumnStatementList());

        // add foreign key and index
        $this->addStatementList($this->referenceListMigrator->getAddForeignKeyStatementList());
        $this->addStatementList($this->indexListMigrator->getAddIndexStatementList());

    }

    /**
     * compares the primary key column and generates migrate primary key statements if needed
     * @return array
     */
    private function getMigratePrimaryKeyStatementList()
    {
        $databasePKList = $this->collectPrimaryKeyColumnList($this->databaseEntity);
        $modelPKList = $this->collectPrimaryKeyColumnList($this->modelEntity);

        $compare = array_diff($databasePKList, $modelPKList);

        if (sizeof($compare) === 0) {
            return [];
        }

        return $this->columnMigrator->getModifyPrimaryKeyStatement($this->databaseEntity, $this->modelEntity);
    }

    /**
     * creates a list of primary key columns for a given EntitySource object
     *
     * @param EntitySource $source
     *
     * @return string[]
     */
    private function collectPrimaryKeyColumnList(EntitySource $source)
    {

        $pkList = [];
        foreach ($source->getAttributeSourceList() as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $pkList[] = $attribute->getDatabaseName();
            }
        }

        foreach ($this->databaseEntity->getReferenceSourceList() as $reference) {
            if ($reference->isPrimaryKey()) {
                foreach ($reference->getReferencedColumnList() as $column) {
                    $pkList[] = $column->getDatabaseName();
                }
            }
        }
        return $pkList;
    }

    /**
     * migrates the attributes of the entity and gathers add modify and drop statements
     * @return void
     */
    private function migrateAttributeList()
    {
        $this->attributeListMigrator = new AttributeListMigrator($this->columnMigrator, $this->databaseEntity->getAttributeSourceList(), $this->modelEntity->getAttributeGeneratorSourceList());
        $this->attributeListMigrator->createAlterStatementList();

    }

    /**
     * migrates the references of the entity and gathers add modify and drop statements for the used columns and add
     * drop constraints statements
     * @return void
     */
    private function migrateReferenceList()
    {
        $this->referenceListMigrator = new ReferenceListMigrator($this->columnMigrator, $this->databaseEntity->getReferenceSourceList(), $this->modelEntity->getReferenceGeneratorSourceList());
        $this->referenceListMigrator->createAlterStatementList();
    }

    /**
     * migrates the indexes of the entity and gathers drop and add statements
     * @return void
     */
    private function migrateIndexList()
    {
        $this->indexListMigrator = new IndexListMigrator($this->columnMigrator, $this->databaseEntity->getIndexSourceList(), $this->modelEntity->getIndexSourceList());
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
    private function addStatementList($statementList, $isAttribute = false)
    {
        foreach ($statementList as $statement) {
            $this->statementList[] = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->databaseEntity->getTable(), $statement);

            // handle delimited table
            if ($this->databaseEntity->isDelimit() and $isAttribute) {
                $this->statementList[] = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->modelEntity->getDelimitTable(), $statement);

            }

            // TODO : handle replication table
        }
    }

}