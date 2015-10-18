<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 14.10.15
 * Time: 15:20
 */

namespace siestaphp\migrator;

use siestaphp\datamodel\reference\ReferencedColumn;
use siestaphp\datamodel\reference\ReferencedColumnSource;
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
    protected $dropForeignKeyStatementList;

    /**
     * @var string[]
     */
    protected $addForeignKeyStatementList;

    /**
     * @var string[]
     */
    protected $addStatementList;

    /**
     * @var string[]
     */
    protected $modifiyStatementList;

    /**
     * @var string[]
     */
    protected $dropStatementList;

    /**
     * @param ColumnMigrator $columnMigrator
     * @param ReferenceSource[] $databaseReferenceList
     * @param ReferenceGeneratorSource[] $modelReferenceList
     */
    public function __construct(ColumnMigrator $columnMigrator, $databaseReferenceList, $modelReferenceList)
    {
        $this->columnMigrator = $columnMigrator;
        $this->databaseReferenceList = $databaseReferenceList;
        $this->modelReferenceList = $modelReferenceList;
        $this->addForeignKeyStatementList = array();
        $this->dropForeignKeyStatementList = array();
        $this->addStatementList = array();
        $this->modifiyStatementList = array();
        $this->dropStatementList = array();
    }

    /**
     * compares the refernces (foreign key) found in the database with the definition in the model and creates alter
     * statements for columns and foreign key constraints
     * @return void
     */
    public function createAlterStatementList()
    {
        $processedDatabaseList = array();

        // iterate references from model and retrieve alter statements
        foreach ($this->modelReferenceList as $modelReference) {

            // check if a corresponding database reference exists
            $databaseReference = $this->getReferenceSourceByConstraintName($this->databaseReferenceList, $modelReference->getConstraintName());

            // retrieve alter statements and add them
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
    }

    /**
     * helper method that allows to find a referenceSource by the constraint name
     *
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
     * compares to references sources and triggers corresponding
     *
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

        $this->addAddForeignKey($referenceSource);

        // add column statements
        foreach ($referenceSource->getReferencedColumnList() as $column) {
            $this->addStatementList[] = $this->columnMigrator->createAddColumnStatement($column);
        }

    }

    /**
     * @param ReferenceSource $referenceSource
     *
     * @return string[]
     */
    private function dropReference(ReferenceSource $referenceSource)
    {
        $this->addDropForeignKey($referenceSource);

        // drop columns
        foreach ($referenceSource->getReferencedColumnList() as $column) {
            $this->dropStatementList[] = $this->columnMigrator->createDropColumnStatement($column);
        }
    }

    /**
     * @param ReferenceSource $asIs
     * @param ReferenceGeneratorSource $toBe
     */
    private function modifyReference(ReferenceSource $asIs, ReferenceGeneratorSource $toBe)
    {

        // check if they are referencing the same column
        if ($asIs->getForeignTable() !== $toBe->getForeignTable()) {
            $this->dropReference($asIs);
            $this->addReference($toBe);
            return;
        }

        // modify columns if needed and check if the
        if ($this->compareReferencedColumnList($asIs->getReferencedColumnList(), $toBe->getReferencedColumnList())) {
            $this->addAddForeignKey($toBe);
            $this->addDropForeignKey($asIs);

            return;
        }

        // compare on update // >> drop constraint, add constraint
        if ($asIs->getOnUpdate() === $toBe->getOnUpdate() and $asIs->getOnDelete() === $toBe->getOnDelete()) {
            return;
        }

        $this->addAddForeignKey($toBe);
        $this->addDropForeignKey($asIs);
    }

    /**
     * @param ReferencedColumnSource[] $asIsList
     * @param ReferencedColumnSource[] $toBeList
     *
     * @return bool
     */
    private function compareReferencedColumnList($asIsList, $toBeList)
    {
        $processedReferencedList = array();

        // stores if the foreign key constraint needs to be drop and recreated
        $needsForeignKeyDropAdd = 0;

        // iterate to be referenced columns and determine if change is needed
        foreach ($toBeList as $toBeColumn) {
            $asIsColumn = $this->getReferencedColumnByDatabaseName($asIsList, $toBeColumn->getDatabaseName());

            $needsForeignKeyDropAdd |= $this->modifyReferencedColumn($asIsColumn, $toBeColumn);

            // if as is column was found store, that we already processed it
            if ($asIsColumn) {
                $processedReferencedList[] = $asIsColumn->getDatabaseName();
            }

        }

        // iterate as is column that have not been processed yet (to drop them)
        foreach ($asIsList as $asIsColumn) {
            if (in_array($asIsColumn->getDatabaseName(), $processedReferencedList)) {
                continue;
            }

            $needsForeignKeyDropAdd |= $this->modifyReferencedColumn($asIsColumn, null);
        }

        return $needsForeignKeyDropAdd === 1;

    }

    /**
     * @param ReferencedColumnSource $asIs
     * @param ReferencedColumnSource $toBe
     *
     * @return bool tells if a drop/create fk constraint is needed
     */
    private function modifyReferencedColumn($asIs, $toBe)
    {
        if ($asIs === null) {
            $this->addStatementList[] = $this->columnMigrator->createAddColumnStatement($toBe);
            return true;
        }

        if ($toBe === null) {
            $this->dropStatementList[] = $this->columnMigrator->createDropColumnStatement($asIs);
            return true;
        }

        // check if columns are identical
        if ($asIs->getDatabaseName() === $toBe->getDatabaseName() and $asIs->isRequired() === $toBe->isRequired()) {
            return false;
        }

        // changing required or type does not required a drop/add foreign key statement
        $this->modifiyStatementList[] = $this->columnMigrator->createModifiyColumnStatement($toBe);
        return false;

    }

    /**
     * @param ReferencedColumn[] $referencedList
     * @param string $databaseName
     *
     * @return ReferencedColumn
     */
    private function getReferencedColumnByDatabaseName(array $referencedList, $databaseName)
    {
        foreach ($referencedList as $referenced) {
            if ($referenced->getDatabaseName() === $databaseName) {
                return $referenced;
            }
        }
        return null;
    }

    /**
     * @param ReferenceSource $source
     */
    private function addDropForeignKey(ReferenceSource $source)
    {
        $this->dropForeignKeyStatementList[] = $this->columnMigrator->createDropForeignKeyStatement($source);
    }

    /**
     * @param ReferenceGeneratorSource $source
     */
    private function addAddForeignKey(ReferenceGeneratorSource $source)
    {
        $this->addForeignKeyStatementList[] = $this->columnMigrator->createAddForeignKeyStatement($source);
    }

    /**
     * @return string[]
     */
    public function getAddForeignKeyStatementList()
    {
        return $this->addForeignKeyStatementList;
    }

    /**
     * @return string[]
     */
    public function getDropForeignKeyStatementList()
    {
        return $this->dropForeignKeyStatementList;
    }

    /**
     * @return string[]
     */
    public function getAddStatementList()
    {
        return $this->addStatementList;
    }

    /**
     * @return string[]
     */
    public function getModifyStatementList()
    {
        return $this->modifiyStatementList;
    }

    /**
     * @return string[]
     */
    public function getDropStatementList()
    {
        return $this->dropStatementList;
    }

}