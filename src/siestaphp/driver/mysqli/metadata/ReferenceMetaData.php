<?php

namespace siestaphp\driver\mysqli\metadata;

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
     * @var string
     */
    protected $constraintName;

    /**
     * @var string[]
     */
    protected $columnNameList;

    /**
     * @var bool
     */
    protected $isNullAble;

    protected $foreignTable;

    protected $foreignColumn;

    protected $onDelete;

    protected $onUpdate;

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->columnNameList = array();
        $this->constraintName = $resultSet->getStringValue(self::CONSTRAINT_NAME);
        $this->foreignTable = $resultSet->getStringValue(self::REFERENCED_TABLE_NAME);
        $this->foreignColumn = $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME);
        $this->onDelete = $resultSet->getStringValue(self::DELETE_RULE);
        $this->onUpdate = $resultSet->getStringValue(self::UPDATE_RULE);
        $this->columnNameList[] = $resultSet->getStringValue(self::COLUMN_NAME);
    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromColumn(ResultSet $resultSet)
    {
        $this->isNullAble = $resultSet->getStringValue(AttributeMetaData::COLUMN_IS_NULLABLE) === AttributeMetaData::COLUMN_IS_NULLABLE_YES;
    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromConstraint(ResultSet $resultSet)
    {
        $this->columnNameList[] = $resultSet->getStringValue(self::COLUMN_NAME);
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
        return in_array($columnName, $this->columnNameList);
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (sizeof($this->columnNameList) === 1) {
            return $this->columnNameList[0];
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

}