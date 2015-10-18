<?php

namespace siestaphp\migrator;

use siestaphp\datamodel\index\Index;
use siestaphp\datamodel\index\IndexGeneratorSource;
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
     * @var IndexGeneratorSource[]
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
     * @param IndexGeneratorSource[] $modelIndexList
     */
    public function __construct(ColumnMigrator $columnMigrator, $databaseIndexList, $modelIndexList)
    {
        $this->columnMigrator = $columnMigrator;
        $this->databaseIndexList = $databaseIndexList;
        $this->modelIndexList = $modelIndexList;

        $this->addIndexStatementList = array();
        $this->dropIndexStatementList = array();
    }

    /**
     * compares both attribute list and request the needed alter statements
     */
    public function createAlterStatementList()
    {
        $processedIndexList = array();

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
     * @param IndexGeneratorSource $modelIndex
     *
     * @return void
     */
    private function createAlterStatement(IndexSource $databaseIndex, IndexGeneratorSource $modelIndex)
    {

        // nothing in db create the index
        if ($databaseIndex === null) {
            $this->createDropIndex($databaseIndex);
            return;
        }

        // nothing in model, drop the index
        if ($modelIndex === null) {
            $this->createAddIndex($modelIndex);
            return;
        }

        $this->compareIndex($databaseIndex, $modelIndex);

    }

    /**
     * @param IndexSource $databaseIndex
     * @param IndexGeneratorSource $modelIndex
     *
     * @return void
     */
    private function compareIndex(IndexSource $databaseIndex, IndexGeneratorSource $modelIndex)
    {
        if ($databaseIndex->getType() !== $modelIndex->getType()) {
            $this->recreateIndex($databaseIndex, $modelIndex);
            return;
        }

        $databaseIndexPartList = $databaseIndex->getIndexPartSourceList();
        $modelIndexPartList = $modelIndex->getIndexPartGeneratorSourceList();

        if (sizeof($databaseIndexPartList) !== sizeof($modelIndexPartList)) {
            $this->recreateIndex($databaseIndex, $modelIndex);
            return;
        }

        foreach ($modelIndexPartList as $modelIndex) {
            $modelIndex->getIndexColumnList();
        }
    }

    /**
     * @param IndexSource $databaseIndex
     * @param IndexGeneratorSource $modelIndex
     */
    private function recreateIndex(IndexSource $databaseIndex, IndexGeneratorSource $modelIndex)
    {
        $this->createDropIndex($databaseIndex);
        $this->createAddIndex($modelIndex);
    }

    /**
     * @param IndexSource $source
     */
    private function createDropIndex(IndexSource $source)
    {
        $this->dropIndexStatementList[] = $this->columnMigrator->createDropIndexStatement($source);
    }

    /**
     * @param IndexGeneratorSource $source
     */
    private function createAddIndex(IndexGeneratorSource $source)
    {
        $this->addIndexStatementList[] = $this->columnMigrator->createAddIndexStatement($source);
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

    private function getIndexPartSourceByName()
    {

    }
}