<?php

declare(strict_types=1);

namespace Siesta\Migration;

use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\Reference;
use Siesta\Model\ReferenceMapping;

/**
 * @author Gregor Müller
 */
class ReferenceListMigrator
{

    /**
     * @var ConstraintMetaData[]
     */
    protected array $constraintMetaDataList;

    /**
     * @var Reference[]
     */
    protected array $referenceList;

    /**
     * @var MigrationStatementFactory
     */
    protected MigrationStatementFactory $migrationStatementFactory;

    /**
     * @var string[]
     */
    protected array $dropForeignKeyStatementList;

    /**
     * @var string[]
     */
    protected array $addForeignKeyStatementList;


    /**
     * ReferenceListMigrator constructor.
     *
     * @param MigrationStatementFactory $migrationStatementFactory
     * @param ConstraintMetaData[] $constraintMetaDataList
     * @param Reference[] $referenceList
     */
    public function __construct(MigrationStatementFactory $migrationStatementFactory, array $constraintMetaDataList, array $referenceList)
    {
        $this->migrationStatementFactory = $migrationStatementFactory;

        $this->constraintMetaDataList = $constraintMetaDataList;
        $this->referenceList = $referenceList;

        $this->addForeignKeyStatementList = [];
        $this->dropForeignKeyStatementList = [];
    }


    /**
     * compares the references (foreign key) found in the database with the definition in the model and creates alter
     * statements for columns and foreign key constraints
     *
     * @return void
     */
    public function createAlterStatementList(): void
    {
        $processedDatabaseList = [];

        // iterate references from model and retrieve alter statements
        foreach ($this->referenceList as $reference) {
            if ($reference->getNoConstraint()) {
                continue;
            }

            // check if a corresponding database reference exists
            $constraint = $this->getConstraintByReference($reference);

            // retrieve alter statements and add them
            $this->createAlterStatement($constraint, $reference);

            // if a database reference has been found add it to the processed list
            if ($constraint) {
                $processedDatabaseList[] = $constraint->getConstraintName();
            }
        }

        // iterate references from database and retrieve alter statements
        foreach ($this->constraintMetaDataList as $constraintMetaData) {
            // check if reference has already been processed
            if (in_array($constraintMetaData->getConstraintName(), $processedDatabaseList)) {
                continue;
            }

            // no corresponding model reference will result in drop statements
            $this->createAlterStatement($constraintMetaData, null);
        }
    }


    /**
     * @param Reference $reference
     *
     * @return null|ConstraintMetaData
     */
    private function getConstraintByReference(Reference $reference): ?ConstraintMetaData
    {
        $constraintName = $reference->getConstraintName();

        foreach ($this->constraintMetaDataList as $constraintMetaData) {
            if ($constraintMetaData->getConstraintName() === $constraintName) {
                return $constraintMetaData;
            }
        }
        return null;
    }


    /**
     * @param ConstraintMetaData|null $constraintMetaData
     * @param Reference|null $reference
     */
    private function createAlterStatement(ConstraintMetaData $constraintMetaData = null, Reference $reference = null): void
    {
        if ($constraintMetaData === null) {
            $this->addCreateReference($reference);
            return;
        }

        if ($reference === null) {
            $this->addDropConstraintStatement($constraintMetaData);
            return;
        }

        $this->compareMapping($constraintMetaData, $reference);
    }


    /**
     * @param ConstraintMetaData $constraintMetaData
     * @param Reference $reference
     */
    private function compareMapping(ConstraintMetaData $constraintMetaData, Reference $reference): void
    {
        // check if they are referencing the same column
        if ($constraintMetaData->getForeignTable() !== $reference->getForeignTable()) {
            $this->addDropConstraintStatement($constraintMetaData);
            $this->addCreateReference($reference);
            return;
        }

        if (sizeof($constraintMetaData->getConstraintMappingList()) !== sizeof($reference->getReferenceMappingList())) {
            $this->addDropConstraintStatement($constraintMetaData);
            $this->addCreateReference($reference);
            return;
        }

        // modify columns if needed and check if the
        if (!$this->areMappingIdentical($constraintMetaData, $reference)) {
            $this->addDropConstraintStatement($constraintMetaData);
            $this->addCreateReference($reference);
            return;
        }

        // compare on update // >> drop constraint, add constraint
        if ($constraintMetaData->getOnUpdate() === $reference->getOnUpdate() and $constraintMetaData->getOnDelete() === $reference->getOnDelete()) {
            return;
        }

        $this->addCreateReference($reference);
        $this->addDropConstraintStatement($constraintMetaData);
    }


    /**
     * @param ConstraintMetaData $constraintMetaData
     * @param Reference $reference
     *
     * @return bool
     */
    protected function areMappingIdentical(ConstraintMetaData $constraintMetaData, Reference $reference): bool
    {
        foreach ($reference->getReferenceMappingList() as $referenceMapping) {
            $constraintMappingMetaDataList = $constraintMetaData->getConstraintMappingList();
            $constraintMappingMetaData = $this->getConstraintMappingByReferenceMapping($constraintMappingMetaDataList, $referenceMapping);
            if ($constraintMappingMetaData === null) {
                return false;
            }
        }
        return true;
    }


    /**
     * @param ConstraintMappingMetaData[] $constraintMappingList
     * @param ReferenceMapping $referenceMapping
     *
     * @return ConstraintMappingMetaData|null
     */
    protected function getConstraintMappingByReferenceMapping(array $constraintMappingList, ReferenceMapping $referenceMapping): ?ConstraintMappingMetaData
    {
        foreach ($constraintMappingList as $constraintMapping) {
            if ($constraintMapping->getLocalColumn() === $referenceMapping->getLocalColumnName() && $constraintMapping->getForeignColumn() === $referenceMapping->getForeignColumnName()) {
                return $constraintMapping;
            }
        }
        return null;
    }


    /**
     * @param ConstraintMetaData $constraintMetaData
     */
    private function addDropConstraintStatement(ConstraintMetaData $constraintMetaData): void
    {
        $dropList = $this->migrationStatementFactory->createDropConstraintStatement($constraintMetaData);
        $this->dropForeignKeyStatementList = array_merge($this->dropForeignKeyStatementList, $dropList);
    }


    /**
     * @param Reference $reference
     */
    private function addCreateReference(Reference $reference): void
    {
        $addList = $this->migrationStatementFactory->createAddReferenceStatement($reference);
        $this->addForeignKeyStatementList = array_merge($this->addForeignKeyStatementList, $addList);
    }


    /**
     * @return string[]
     */
    public function getAddForeignKeyStatementList(): array
    {
        return $this->addForeignKeyStatementList;
    }


    /**
     * @return string[]
     */
    public function getDropForeignKeyStatementList(): array
    {
        return $this->dropForeignKeyStatementList;
    }

}