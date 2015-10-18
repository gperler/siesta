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
     * @param ColumnMigrator $migrator
     * @param EntitySource $databaseEntity
     * @param EntityGeneratorSource $modelEntity
     */
    public function __construct(ColumnMigrator $migrator, EntitySource $databaseEntity, EntityGeneratorSource $modelEntity)
    {
        $this->columnMigrator = $migrator;
        $this->databaseEntity = $databaseEntity;
        $this->modelEntity = $modelEntity;
        $this->statementList = array();
    }

    /**
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
     * brings the alter table statements into the right order
     */
    private function assembleStatementList()
    {

        // drop foreign key constraints
        $this->addStatementList($this->referenceListMigrator->getDropForeignKeyStatementList());
        // drop indexes

        // add columns
        $this->addStatementList($this->attributeListMigrator->getAddStatementList());
        $this->addStatementList($this->attributeListMigrator->getModifyStatementList());

        // modify columns
        $this->addStatementList($this->referenceListMigrator->getAddStatementList());
        $this->addStatementList($this->referenceListMigrator->getModifyStatementList());

        // modify primary key
        $this->addStatementList($this->getMigratePrimaryKeyStatementList());

        // drop columns
        $this->addStatementList($this->attributeListMigrator->getDropStatementList());
        $this->addStatementList($this->referenceListMigrator->getDropStatementList());

        // add foreign key
        $this->addStatementList($this->referenceListMigrator->getAddForeignKeyStatementList());
        // add indexes

    }

    /**
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

    private function migrateAttributeList()
    {

        $this->attributeListMigrator = new AttributeListMigrator($this->columnMigrator, $this->databaseEntity->getAttributeSourceList(), $this->modelEntity->getAttributeGeneratorSourceList());
        $this->attributeListMigrator->createAlterStatementList();

    }

    private function migrateReferenceList()
    {
        $this->referenceListMigrator = new ReferenceListMigrator($this->columnMigrator, $this->databaseEntity->getReferenceSourceList(), $this->modelEntity->getReferenceGeneratorSourceList());
        $this->referenceListMigrator->createAlterStatementList();
    }

    private function migrateIndexList()
    {

    }

    /**
     * @param string[] $statementList
     */
    private function addStatementList($statementList)
    {
        foreach ($statementList as $statement) {
            $this->statementList[] = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->databaseEntity->getTable(), $statement);

            // handle delimited table
            if ($this->databaseEntity->isDelimit()) {

            }
        }
    }

}