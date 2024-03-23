<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Database\StoredProcedureDefinition;
use Siesta\Util\ArrayUtil;
use Siesta\Util\File;
use SiestaTest\TestUtil\TestException;

/**
 * @author Gregor Müller
 */
class TestDatabaseMetaData implements DatabaseMetaData
{

    const TABLE_LIST = "tableList";

    const STORED_PROCEDURE_LIST = "storedProcedureList";

    /**
     * @var TestTableMetaData[]
     */
    protected $tableList;

    /**
     * @var TestStoredProcedureDefinition[]
     */
    private $storedProcedureList;

    /**
     * TestDatabaseMetaData constructor.
     *
     * @param File $modelFile
     *
     * @throws TestException
     */
    public function __construct(File $modelFile)
    {
        if (!$modelFile->exists()) {
            throw new TestException("model file does not exist " . $modelFile->getAbsoluteFileName());
        }
        $valueList = $modelFile->loadAsJSONArray();

        $this->parseTableMetaData($valueList);
        $this->parseStoredProcedureMetaData($valueList);
    }

    /**
     * @param array|null $valueList
     * @throws TestException
     */
    private function parseTableMetaData(array $valueList = null)
    {
        $tableList = ArrayUtil::getFromArray($valueList, self::TABLE_LIST);
        if ($tableList === null || !is_array($tableList)) {
            throw new TestException("model file empty or does not contain " . self::TABLE_LIST);
        }
        foreach ($tableList as $table) {
            $testTable = new TestTableMetaData($table);
            $this->tableList[$testTable->getName()] = new TestTableMetaData($table);
        }
    }

    /**
     * @param array|null $valueList
     */
    private function parseStoredProcedureMetaData(array $valueList = null)
    {
        $storedProcedureList = ArrayUtil::getFromArray($valueList, self::STORED_PROCEDURE_LIST);
        if ($storedProcedureList === null || !is_array($storedProcedureList)) {
            $this->storedProcedureList = [];
            return;
        }
        foreach ($storedProcedureList as $storedProcedure) {
            $this->storedProcedureList[] = new TestStoredProcedureDefinition($storedProcedure);
        }
    }

    /**
     * @return void
     */
    public function refresh(): void
    {

    }

    /**
     * @return TestTableMetaData[]
     */
    public function getTableList(): array
    {
        return $this->tableList;
    }

    /**
     * @param string $tableName
     *
     * @return null|TestTableMetaData
     */
    public function getTableByName(string $tableName): ?TableMetaData
    {
        foreach ($this->tableList as $table) {
            if ($table->getName() === $tableName) {
                return $table;
            }
        }
        return null;
    }

    /**
     * @return StoredProcedureDefinition[]
     */
    public function getStoredProcedureList(): array
    {
        return $this->storedProcedureList;
    }

}