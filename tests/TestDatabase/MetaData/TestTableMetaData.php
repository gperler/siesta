<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Util\ArrayUtil;

class TestTableMetaData implements TableMetaData
{

    const NAME = "name";

    const COLUMN_LIST = "columnList";

    const INDEX_LIST = "indexList";

    const CONSTRAINT_LIST = "constraintList";

    /**
     * @var TestColumnMetaData[]
     */
    protected $columnList;

    /**
     * @var TestIndexMetaData[]
     */
    protected $indexList;

    /**
     * @var TestConstraintMetaData[]
     */
    protected $constraintList;

    /**
     * @var array
     */
    protected $valueList;

    /**
     * TestTableMetaData constructor.
     *
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;

        $this->columnList = [];
        foreach (ArrayUtil::getFromArray($valueList, self::COLUMN_LIST) as $column) {
            $testColumn = new TestColumnMetaData($column);
            $this->columnList[$testColumn->getDBName()] = new TestColumnMetaData($column);
        }

        $this->indexList = [];
        foreach (ArrayUtil::getFromArray($valueList, self::INDEX_LIST) as $index) {
            $this->indexList[] = new TestIndexMetaData($index);
        }

        $this->constraintList = [];
        foreach (ArrayUtil::getFromArray($valueList, self::CONSTRAINT_LIST) as $constraint) {
            $testConstraint = new TestConstraintMetaData($constraint);
            $this->constraintList[$testConstraint->getName()] = $testConstraint;
        }
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::NAME);
    }

    /**
     * @return TestColumnMetaData[]
     */
    public function getColumnList() : array
    {
        return $this->columnList;
    }

    /**
     * @param string $name
     *
     * @return null|TestColumnMetaData
     */
    public function getColumnByName(string $name): ?ColumnMetaData
    {
        foreach ($this->columnList as $column) {
            if ($column->getDBName() === $name) {
                return $column;
            }
        }
        return null;
    }

    /**
     * @return TestConstraintMetaData[]
     */
    public function getConstraintList() : array
    {
        return $this->constraintList;
    }

    /**
     * @param string $name
     *
     * @return null|TestConstraintMetaData
     */
    public function getConstraintByName(string $name): ?ConstraintMetaData
    {
        foreach ($this->constraintList as $constraintMetaData) {
            if ($constraintMetaData->getName() === $name) {
                return $constraintMetaData;
            }
        }
        return null;
    }

    /**
     * @return TestIndexMetaData[]
     */
    public function getIndexList() : array
    {
        return $this->indexList;
    }

    /**
     * @param string $indexName
     * @return null|TestIndexMetaData
     */
    public function getIndexByName(string $indexName): ?IndexMetaData
    {
        foreach ($this->indexList as $indexMetaData) {
            if ($indexMetaData->getName() === $indexName) {
                return $indexMetaData;
            }
        }
        return null;
    }

    /**
     * @return TestColumnMetaData[]
     */
    public function getPrimaryKeyAttributeList() : array
    {
        $pkList = [];
        foreach ($this->columnList as $columnMetaData) {
            if ($columnMetaData->getIsPrimaryKey()) {
                $pkList[] = $columnMetaData;
            }
        }
        return $pkList;
    }

    public function getDataBaseSpecific() : array
    {
        return [];
    }

}