<?php

namespace siestaphp\migrator;

use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\driver\ColumnMigrator;

/**
 * Class IndexListMigrator
 * @package siestaphp\migrator
 */
class IndexListMigrator
{

    /**
     * @var ColumnMigrator
     */
    protected $columnMigrator;

    /**
     * @var IndexSource[]
     */
    protected $databaseIndexList;

    /**
     * @var IndexSource[]
     */
    protected $modelIndexList;

    /**
     * @var string[]
     */
    protected $addIndexStatementList;

    /**
     * @var string[]
     */
    protected $dropIndexStatementList;

    /**
     * @param ColumnMigrator $columnMigrator
     * @param IndexSource[] $databaseIndexList
     * @param IndexSource[] $modelIndexList
     */
    public function __construct(ColumnMigrator $columnMigrator, $databaseIndexList, $modelIndexList)
    {
        $this->columnMigrator = $columnMigrator;
        $this->databaseIndexList = $databaseIndexList;
        $this->modelIndexList = $modelIndexList;

        $this->addIndexStatementList = [];
        $this->dropIndexStatementList = [];
    }

    /**
     * compares both attribute list and request the needed alter statements
     * @return void
     */
    public function createAlterStatementList()
    {
        $processedIndexList = [];

        // iterate attributes from model and retrieve alter statements
        foreach ($this->modelIndexList as $modelIndex) {
            // check if a corresponding database attribute exists
            $databaseIndex = $this->getIndexSourceByName($this->databaseIndexList, $modelIndex->getName());

            // retrieve alter statements from migrator and add them
            $this->createAlterStatement($databaseIndex, $modelIndex);

            // if a database attribute has been found add it to the processed list
            if ($databaseIndex) {
                $processedIndexList[] = $databaseIndex->getName();
            }
        }

        // iterate attributes from database and retrieve alter statements
        foreach ($this->databaseIndexList as $databaseIndex) {

            // check if attribute has already been processed
            if (in_array($databaseIndex->getName(), $processedIndexList)) {
                continue;
            }
            // no corresponding model attribute will result in drop statements
            $this->createAlterStatement($databaseIndex, null);
        }

    }

    /**
     * @param IndexSource $databaseIndex
     * @param IndexSource $modelIndex
     *
     * @return void
     */
    private function createAlterStatement($databaseIndex, $modelIndex)
    {

        // nothing in db create the index
        if ($databaseIndex === null) {
            $this->createAddIndex($modelIndex);
            return;
        }

        // nothing in model, drop the index
        if ($modelIndex === null) {
            $this->createDropIndex($databaseIndex);
            return;
        }

        $this->compareIndex($databaseIndex, $modelIndex);

    }

    /**
     * @param IndexSource $databaseIndex
     * @param IndexSource $modelIndex
     *
     * @return void
     */
    private function compareIndex(IndexSource $databaseIndex, IndexSource $modelIndex)
    {
        if ($databaseIndex->getType() !== $modelIndex->getType()) {
            $this->recreateIndex($databaseIndex, $modelIndex);
            return;
        }

        $databasePartList = $databaseIndex->getIndexPartSourceList();
        $modelPartList = $modelIndex->getIndexPartSourceList();

        if (sizeof($databasePartList) !== sizeof($modelPartList)) {
            $this->recreateIndex($databaseIndex, $modelIndex);
            return;
        }

        if ($this->needsRecreationOfIndex($databasePartList, $modelPartList)) {
            $this->recreateIndex($databaseIndex, $modelIndex);
        }

    }

    /**
     * @param IndexPartSource[] $databaseColumnList
     * @param IndexPartSource[] $modelColumnList
     *
     * @return bool
     */
    private function needsRecreationOfIndex($databaseColumnList, $modelColumnList)
    {
        foreach ($modelColumnList as $modelColumn) {
            $databaseIndex = $this->getIndexPartByColumnName($databaseColumnList, $modelColumn->getColumnName());
            if ($databaseIndex === null) {
                return true;
            }

            if ($databaseIndex->getLength() !== $modelColumn->getLength()) {
                return true;
            }

        }
        return false;
    }

    /**
     * @param IndexPartSource[] $indexPartList
     * @param string $columnName
     *
     * @return IndexPartSource|null
     */
    private function getIndexPartByColumnName(array $indexPartList, $columnName)
    {
        foreach ($indexPartList as $indexPart) {
            if ($indexPart->getColumnName() === $columnName) {
                return $indexPart;
            }
        }
        return null;
    }

    /**
     * @param IndexSource $databaseIndex
     * @param IndexSource $modelIndex
     *
     * @return void
     */
    private function recreateIndex(IndexSource $databaseIndex, IndexSource $modelIndex)
    {
        $this->createDropIndex($databaseIndex);
        $this->createAddIndex($modelIndex);
    }

    /**
     * @param IndexSource $source
     *
     * @return void
     */
    private function createDropIndex(IndexSource $source)
    {
        $this->dropIndexStatementList[] = $this->columnMigrator->createDropIndexStatement($source);
    }

    /**
     * @param IndexSource $source
     *
     * @return void
     */
    private function createAddIndex(IndexSource $source)
    {
        $this->addIndexStatementList[] = $this->columnMigrator->createAddIndexStatement($source);
    }

    /**
     * @return string[]
     */
    public function getAddIndexStatementList()
    {
        return $this->addIndexStatementList;
    }

    /**
     * @return string[]
     */
    public function getDropIndexStatementList()
    {
        return $this->dropIndexStatementList;
    }

    /**
     * @param IndexSource[] $indexSourceList
     * @param string $name
     *
     * @return IndexSource|null
     */
    private function getIndexSourceByName($indexSourceList, $name)
    {
        foreach ($indexSourceList as $index) {
            if ($index->getName() === $name) {
                return $index;
            }
        }
        return null;
    }

}