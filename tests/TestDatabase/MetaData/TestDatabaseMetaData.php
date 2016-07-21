<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Util\ArrayUtil;
use Siesta\Util\File;
use SiestaTest\TestUtil\TestException;

/**
 * @author Gregor Müller
 */
class TestDatabaseMetaData implements DatabaseMetaData
{

    const TABLE_LIST = "tableList";

    /**
     * @var TestTableMetaData[]
     */
    protected $tableList;

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

        $tableList = ArrayUtil::getFromArray($valueList, self::TABLE_LIST);

        if ($tableList === null || !is_array($tableList)) {
            throw new TestException("model file empty or does not contain " . self::TABLE_LIST);
        }
        $this->parseJSON($tableList);

    }

    /**
     * @param array $tableList
     */
    protected function parseJSON(array $tableList)
    {
        foreach ($tableList as $table) {
            $this->tableList[] = new TestTableMetaData($table);
        }
    }

    public function refresh()
    {
        // TODO: Implement refresh() method.
    }

    /**
     * @return TestTableMetaData[]
     */
    public function getTableList() : array
    {
        return $this->tableList;
    }

    /**
     * @param string $tableName
     *
     * @return null|TestTableMetaData
     */
    public function getTableByName(string $tableName)
    {
        foreach ($this->tableList as $table) {
            if ($table->getName() === $tableName) {
                return $table;
            }
        }
        return null;
    }

    public function getStoredProcedureList() : array
    {
        return [];
    }

}