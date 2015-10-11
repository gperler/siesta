<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\reference\MappingSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\driver\ResultSet;

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
     * @var bool
     */
    protected $isNullAble;

    protected $foreignTable;

    protected $foreignColumn;

    protected $onDelete;

    protected $onUpdate;

    protected $isPrimaryKey;

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->mappingList = array();
        $this->constraintName = $resultSet->getStringValue(self::CONSTRAINT_NAME);
        $this->foreignTable = $resultSet->getStringValue(self::REFERENCED_TABLE_NAME);
        $this->foreignColumn = $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME);
        $this->mappingList[] = new ReferenceMappingMetaData($resultSet->getStringValue(self::COLUMN_NAME), $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME));

    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromColumn(ResultSet $resultSet)
    {
        $this->isNullAble = $resultSet->getStringValue(AttributeMetaData::COLUMN_IS_NULLABLE) === AttributeMetaData::COLUMN_IS_NULLABLE_YES;
        $this->isPrimaryKey = $resultSet->getStringValue(AttributeMetaData::COLUMN_KEY) === AttributeMetaData::COLUMN_KEY_PRIMARY_KEY;
    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromConstraint(ResultSet $resultSet)
    {
        $this->mappingList[] = new ReferenceMappingMetaData($resultSet->getStringValue(self::COLUMN_NAME), $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME));
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
     * @return mixed
     */
    public function getForeignClass()
    {
        return $this->foreignTable;
    }

    /**
     * @return mixed
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
        return strtoupper($this->onDelete);
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return strtoupper($this->onUpdate);
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

}