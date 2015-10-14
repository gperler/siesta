<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 14.10.15
 * Time: 15:20
 */

namespace siestaphp\migrator;

use siestaphp\datamodel\reference\MappingSource;
use siestaphp\datamodel\reference\ReferencedColumn;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\driver\ColumnMigrator;

/**
 * Class ReferenceListMigrator
 * @package siestaphp\migrator
 */
class ReferenceListMigrator
{

    /**
     * @var ReferenceSource[]
     */
    protected $databaseReferenceList;

    /**
     * @var ReferenceGeneratorSource[]
     */
    protected $modelReferenceList;

    /**
     * @var ColumnMigrator
     */
    protected $columnMigrator;

    /**
     * @var string[]
     */
    protected $alterStatementList;

    /**
     * @param ColumnMigrator $columnMigrator
     * @param ReferenceSource[] $databaseReferenceList
     * @param ReferenceGeneratorSource[] $modelReferenceList
     */
    public function __construct(ColumnMigrator $columnMigrator, $databaseReferenceList, $modelReferenceList)
    {
        $this->columnMigrator = $columnMigrator;
        $this->databaseReferenceList = $databaseReferenceList;
        $this->modelReferenceListList = $modelReferenceList;
        $this->alterStatementList = array();
    }

    /**
     * @return string[]
     */
    public function migrateAttributeList()
    {
        $processedDatabaseList = array();

        // iterate references from model and retrieve alter statements
        foreach ($this->modelReferenceList as $modelReference) {

            // check if a corresponding database reference exists
            $databaseReference = $this->getReferenceSourceByConstraintName($this->databaseReferenceList, $modelReference->getConstraintName());

            // retrieve alter statements from migrator and add them
            $this->createAlterStatement($databaseReference, $modelReference);

            // if a database reference has been found add it to the processed list
            if ($databaseReference) {
                $processedDatabaseList[] = $databaseReference->getConstraintName();
            }
        }

        // iterate references from database and retrieve alter statements
        foreach ($this->databaseReferenceList as $databaseReference) {

            // check if reference has already been processed
            if (in_array($databaseReference->getConstraintName(), $processedDatabaseList)) {
                continue;
            }

            // no corresponding model reference will result in drop statements
            $this->createAlterStatement($databaseReference, null);
        }

        return $this->alterStatementList;
    }

    /**
     * @param ReferenceSource[] $referenceSourceList
     * @param string $constraintName
     *
     * @return ReferenceSource|null
     */
    private function getReferenceSourceByConstraintName($referenceSourceList, $constraintName)
    {
        foreach ($referenceSourceList as $reference) {
            if ($reference->getConstraintName() === $constraintName) {
                return $reference;
            }
        }
        return null;
    }

    /**
     * @param ReferenceSource $asIs
     * @param ReferenceGeneratorSource $toBe
     */
    private function createAlterStatement($asIs, $toBe)
    {

        if ($asIs === null) {
            $this->addReference($toBe);
            return;
        }

        if ($toBe === null) {
            $this->dropReference($asIs);
            return;
        }

        $this->modifyReference($asIs, $toBe);

    }

    /**
     * @param ReferenceGeneratorSource $referenceSource
     *
     * @return string[]
     */
    private function addReference(ReferenceGeneratorSource $referenceSource)
    {

        // add column statements
        foreach ($referenceSource->getReferencedColumnList() as $column) {
            $addColumStatement = $this->columnMigrator->createAddColumnStatement($column);
            $this->addStatement($addColumStatement);

        }

        $addFKStatement = $this->columnMigrator->createAddForeignKeyStatement($referenceSource);
        $this->addStatement($addFKStatement);
    }

    /**
     * @param ReferenceSource $referenceSource
     *
     * @return string[]
     */
    private function dropReference(ReferenceSource $referenceSource)
    {
        $dropFKStatement = $this->columnMigrator->createDropForeignKeyStatemtn($referenceSource);
        $this->addStatement($dropFKStatement);

        foreach ($referenceSource->getMappingSourceList() as $mapping) {
            $dropColumnStatement = $this->columnMigrator->createDropColumnStatement($mapping->getDatabaseName());
            $this->addStatement($dropColumnStatement);
        }
    }

    /**
     * @param ReferenceSource $asIs
     * @param ReferenceGeneratorSource $toBe
     */
    private function modifyReference(ReferenceSource $asIs, ReferenceGeneratorSource $toBe) {

        if ($asIs->getForeignTable() !== $toBe->getForeignTable()) {
            $this->dropReference($asIs);
            $this->addReference($toBe);
            return;
        }

        $asIs->getReferencedColumnList();
        $toBe->getReferencedColumnList();


        if ($asIs->getOnUpdate() !== $toBe->getOnUpdate() or $asIs->getOnDelete() !== $toBe->getOnDelete()) {

        }
    }

    /**
     * @param MappingSource[] $asIsList
     * @param ReferencedColumn[] $toBeList
     */
    private function compareColumns($asIsList, $toBeList) {
        $asIsList[0]->getForeignName();
        $toBeList[0]->getReferencedDatabaseName();

    }


    private function referencedColumns() {

    }

    /**
     * @param string $statement
     */
    private function addStatement($statement)
    {
        $this->alterStatementList[] = $statement;
    }
}