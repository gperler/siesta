<?php
declare(strict_types=1);

namespace Siesta\Migration;

use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\Index;
use Siesta\Model\IndexPart;

/**
 * @author Gregor Müller
 */
class IndexListMigrator
{

    /**
     * @var MigrationStatementFactory
     */
    protected MigrationStatementFactory $migrationStatementFactory;

    /**
     * @var IndexMetaData[]
     */
    protected array $indexMetaDataList;

    /**
     * @var Index[]
     */
    protected array $indexList;

    /**
     * @var string[]
     */
    protected array $addIndexStatementList;

    /**
     * @var string[]
     */
    protected array $dropIndexStatementList;

    /**
     * IndexListMigrator constructor.
     *
     * @param MigrationStatementFactory $migrationStatementFactory
     * @param IndexMetaData[] $indexMetaDataList
     * @param Index[] $indexList
     */
    public function __construct(MigrationStatementFactory $migrationStatementFactory, array $indexMetaDataList, array $indexList)
    {
        $this->migrationStatementFactory = $migrationStatementFactory;
        $this->indexMetaDataList = $indexMetaDataList;
        $this->indexList = $indexList;

        $this->addIndexStatementList = [];
        $this->dropIndexStatementList = [];
    }

    /**
     * compares both attribute list and request the needed alter statements
     * @return void
     */
    public function createAlterStatementList(): void
    {
        $processedIndexList = [];

        foreach ($this->indexList as $index) {

            $indexMetaData = $this->getIndexMetaDataByIndex($index);

            $this->createAlterStatement($indexMetaData, $index);

            if ($indexMetaData) {
                $processedIndexList[] = $indexMetaData->getName();
            }
        }

        foreach ($this->indexMetaDataList as $indexMetaData) {

            if (in_array($indexMetaData->getName(), $processedIndexList)) {
                continue;
            }

            $this->createAlterStatement($indexMetaData, null);
        }

    }

    /**
     * @param IndexMetaData|null $indexMetaData
     * @param Index|null $index
     */
    private function createAlterStatement(IndexMetaData $indexMetaData = null, Index $index = null): void
    {

        // nothing in db create the index
        if ($indexMetaData === null) {
            $this->createAddIndex($index);
            return;
        }

        // nothing in model, drop the index
        if ($index === null) {
            $this->createDropIndex($indexMetaData);
            return;
        }

        $this->compareIndex($indexMetaData, $index);

    }

    /**
     * @param IndexMetaData $indexMetaData
     * @param Index $index
     */
    private function compareIndex(IndexMetaData $indexMetaData, Index $index): void
    {
        if (strtoupper($indexMetaData->getType()) !== strtoupper($index->getIndexType())) {
            $this->recreateIndex($indexMetaData, $index);
            return;
        }

        if ($indexMetaData->getIsUnique() !== $index->getIsUnique()) {
            $this->recreateIndex($indexMetaData, $index);
            return;
        }

        $databasePartList = $indexMetaData->getIndexPartList();
        $modelPartList = $index->getIndexPartList();

        if (sizeof($databasePartList) !== sizeof($modelPartList)) {
            $this->recreateIndex($indexMetaData, $index);
            return;
        }

        if ($this->needsRecreationOfIndex($databasePartList, $modelPartList)) {
            $this->recreateIndex($indexMetaData, $index);
        }

    }

    /**
     * @param IndexPartMetaData[] $databaseIndexPart
     * @param IndexPart[] $modelIndexPartList
     *
     * @return bool
     */
    private function needsRecreationOfIndex(array $databaseIndexPart, array $modelIndexPartList): bool
    {
        foreach ($modelIndexPartList as $modelIndexPart) {
            $databaseIndex = $this->getIndexPartByColumnName($databaseIndexPart, $modelIndexPart->getColumnName());
            if ($databaseIndex === null) {
                return true;
            }

            $dbLength = $databaseIndex->getLength() ? $databaseIndex->getLength() : null;
            $modelLength = $modelIndexPart->getLength() ? $modelIndexPart->getLength() : null;
            if ($dbLength !== $modelLength) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param IndexPartMetaData[] $databaseIndexPartList
     * @param string $columnName
     *
     * @return IndexPartMetaData|null
     */
    private function getIndexPartByColumnName(array $databaseIndexPartList, string $columnName): ?IndexPartMetaData
    {
        foreach ($databaseIndexPartList as $databaseIndexPart) {
            if ($databaseIndexPart->getColumnName() === $columnName) {
                return $databaseIndexPart;
            }
        }
        return null;
    }

    /**
     * @param IndexMetaData $indexMetaData
     * @param Index $index
     */
    private function recreateIndex(IndexMetaData $indexMetaData, Index $index): void
    {
        $this->createDropIndex($indexMetaData);
        $this->createAddIndex($index);
    }

    /**
     * @param IndexMetaData $indexMetaData
     */
    private function createDropIndex(IndexMetaData $indexMetaData): void
    {
        $dropList = $this->migrationStatementFactory->createDropIndexStatement($indexMetaData);
        $this->dropIndexStatementList = array_merge($this->dropIndexStatementList, $dropList);
    }

    /**
     * @param Index $index
     */
    private function createAddIndex(Index $index): void
    {
        $addList = $this->migrationStatementFactory->createAddIndexStatement($index);
        $this->addIndexStatementList = array_merge($this->addIndexStatementList, $addList);
    }

    /**
     * @return string[]
     */
    public function getAddIndexStatementList(): array
    {
        return $this->addIndexStatementList;
    }

    /**
     * @return string[]
     */
    public function getDropIndexStatementList(): array
    {
        return $this->dropIndexStatementList;
    }

    /**
     * @param Index $index
     *
     * @return null|IndexMetaData
     */
    private function getIndexMetaDataByIndex(Index $index): ?IndexMetaData
    {
        foreach ($this->indexMetaDataList as $indexMetaData) {
            if ($indexMetaData->getName() === $index->getName()) {
                return $indexMetaData;
            }
        }
        return null;
    }

}