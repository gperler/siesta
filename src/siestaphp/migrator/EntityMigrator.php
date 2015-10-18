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
        $this->statementList = array();
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
     */
    private function assembleStatementList()
    {

        // drop foreign key constraints
        $this->addStatementList($this->referenceListMigrator->getDropForeignKeyStatementList());
        // drop indexes

        // add columns
        $this->addStatementList($this->attributeListMigrator->getAddStatementList(), true);
        $this->addStatementList($this->attributeListMigrator->getModifyStatementList(), true);

        // modify columns
        $this->addStatementList($this->referenceListMigrator->getAddStatementList());
        $this->addStatementList($this->referenceListMigrator->getModifyStatementList());

        // modify primary key
        $this->addStatementList($this->getMigratePrimaryKeyStatementList());

        // drop columns
        $this->addStatementList($this->attributeListMigrator->getDropStatementList(), true);
        $this->addStatementList($this->referenceListMigrator->getDropStatementList());

        // add foreign key
        $this->addStatementList($this->referenceListMigrator->getAddForeignKeyStatementList());
        // add indexes

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
            return array();
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

        $pkList = array();
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
     */
    private function migrateAttributeList()
    {
        $this->attributeListMigrator = new AttributeListMigrator($this->columnMigrator, $this->databaseEntity->getAttributeSourceList(), $this->modelEntity->getAttributeGeneratorSourceList());
        $this->attributeListMigrator->createAlterStatementList();

    }

    /**
     * migrates the references of the entity and gathers add modify and drop statements for the used columns and add
     * drop constraints statements
     */
    private function migrateReferenceList()
    {
        $this->referenceListMigrator = new ReferenceListMigrator($this->columnMigrator, $this->databaseEntity->getReferenceSourceList(), $this->modelEntity->getReferenceGeneratorSourceList());
        $this->referenceListMigrator->createAlterStatementList();
    }

    private function migrateIndexList()
    {

    }

    /**
     * adds a statement and replaces the table place holder against the real table. Considers delimited tables.
     *
     * @param string[] $statementList
     * @param bool $isAttribute delimiter tables are only changed for the attributes
     */
    private function addStatementList($statementList, $isAttribute = false)
    {
        foreach ($statementList as $statement) {
            $this->statementList[] = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->databaseEntity->getTable(), $statement);

            // handle delimited table
            if ($this->databaseEntity->isDelimit() and $isAttribute) {

            }
        }
    }

}