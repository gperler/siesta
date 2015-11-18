<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\reference\MappingSource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\driver\ResultSet;
use siestaphp\naming\NamingService;

/**
 * Class ReferenceMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class ReferenceMetaData implements ReferenceSource
{

    const COLUMN_NAME = "COLUMN_NAME";

    const CONSTRAINT_NAME = "CONSTRAINT_NAME";

    const REFERENCED_TABLE_NAME = "REFERENCED_TABLE_NAME";

    const REFERENCED_COLUMN_NAME = "REFERENCED_COLUMN_NAME";

    const DELETE_RULE = "DELETE_RULE";

    const UPDATE_RULE = "UPDATE_RULE";

    /**
     * @param ResultSet $resultSet
     *
     * @return string
     */
    public static function getConstraintNameFromResultSet(ResultSet $resultSet)
    {
        return $resultSet->getStringValue(self::CONSTRAINT_NAME);
    }

    /**
     * @param ResultSet $resultSet
     *
     * @return bool
     */
    public static function considerConstraints(ResultSet $resultSet)
    {
        return $resultSet->getStringValue(self::REFERENCED_TABLE_NAME) !== null;
    }

    /**
     * @var string
     */
    protected $constraintName;

    /**
     * @var ReferenceMappingMetaData[]
     */
    protected $mappingList;

    /**
     * @var ReferencedColumnMetaData[]
     */
    protected $referencedColumnList;

    /**
     * @var bool
     */
    protected $isNullAble;

    /**
     * @var string
     */
    protected $foreignTable;

    /**
     * @var string
     */
    protected $onDelete;

    /**
     * @var string
     */
    protected $onUpdate;

    /**
     * @var bool
     */
    protected $isPrimaryKey;

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {

        $this->mappingList = [];
        $this->referencedColumnList = [];

        $this->constraintName = $resultSet->getStringValue(self::CONSTRAINT_NAME);
        $this->foreignTable = $resultSet->getStringValue(self::REFERENCED_TABLE_NAME);

        $this->mappingList[] = new ReferenceMappingMetaData($resultSet->getStringValue(self::COLUMN_NAME), $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME));
        $this->referencedColumnList[] = new ReferencedColumnMetaData($resultSet->getStringValue(self::COLUMN_NAME), $this->foreignTable, $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME));
    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromColumn(ResultSet $resultSet)
    {
        $this->isNullAble = $resultSet->getStringValue(AttributeMetaData::COLUMN_IS_NULLABLE) === AttributeMetaData::COLUMN_IS_NULLABLE_YES;
        $this->isPrimaryKey = $resultSet->getStringValue(AttributeMetaData::COLUMN_KEY) === AttributeMetaData::COLUMN_KEY_PRIMARY_KEY;

        $columName = $resultSet->getStringValue(AttributeMetaData::COLUMN_NAME);

        $referencedColumn = $this->getReferencedColumnByName($columName);
        $referencedColumn->updateFromColumn($resultSet);

        $mapping = $this->getMappingByColumnName($columName);
        $mapping->setDatabaseType($referencedColumn->getDatabaseType());

    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromConstraint(ResultSet $resultSet)
    {
        $this->mappingList[] = new ReferenceMappingMetaData($resultSet->getStringValue(self::COLUMN_NAME), $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME));
        $this->referencedColumnList[] = new ReferencedColumnMetaData($resultSet->getStringValue(self::COLUMN_NAME), $this->foreignTable, $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME));
    }

    /**
     * @param string $columnName
     *
     * @return ReferenceMappingMetaData
     */
    private function getMappingByColumnName($columnName)
    {
        foreach ($this->mappingList as $mapping) {
            if ($mapping->getName() === $columnName) {
                return $mapping;
            }
        }
        return null;
    }

    /**
     * tells if this reference has a referenced column with given column name
     *
     * @param $columnName
     *
     * @return bool
     */
    public function refersColumnName($columnName)
    {
        $reference = $this->getReferencedColumnByName($columnName);
        return ($reference !== null);
    }

    /**
     * @param string $columnName
     *
     * @return ReferencedColumnMetaData
     */
    private function getReferencedColumnByName($columnName)
    {
        foreach ($this->referencedColumnList as $referencedColumn) {
            if ($referencedColumn->getName() === $columnName) {
                return $referencedColumn;
            }
        }
        return null;
    }

    /**
     * @return ReferencedColumnSource[]
     */
    public function getReferencedColumnList()
    {
        return $this->referencedColumnList;
    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateConstraintData(ResultSet $resultSet)
    {
        $this->onUpdate = $resultSet->getStringValue(self::UPDATE_RULE);
        $this->onDelete = $resultSet->getStringValue(self::DELETE_RULE);
    }

    /**
     * @return string
     */
    public function getConstraintName()
    {
        return $this->constraintName;
    }

    /**
     * @param $columnName
     *
     * @return bool
     */
    public function usesColumn($columnName)
    {
        foreach ($this->mappingList as $mapping) {
            if ($mapping->getName() === $columnName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return MappingSource[]
     */
    public function getMappingSourceList()
    {
        return $this->mappingList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (sizeof($this->mappingList) === 1) {
            return $this->mappingList[0]->getName();
        }
        return $this->constraintName;
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getForeignClass()
    {
        return NamingService::getClassName($this->foreignTable);
    }

    /**
     * @return string
     */
    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return !$this->isNullAble;
    }

    /**
     * @return string
     */
    public function getOnDelete()
    {
        return strtolower($this->onDelete);
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return strtolower($this->onUpdate);
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }
}