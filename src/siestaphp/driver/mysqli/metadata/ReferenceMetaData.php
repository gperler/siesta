<?php


namespace siestaphp\driver\mysqli\metadata;


use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\driver\ResultSet;

/**
 * Class ReferenceMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class ReferenceMetaData implements ReferenceSource {

    const REFERENCED_TABLE_NAME = "REFERENCED_TABLE_NAME";
    const REFERENCED_COLUMN_NAME = "REFERENCED_COLUMN_NAME";
    const DELETE_RULE = "DELETE_RULE";
    const UPDATE_RULE = "UPDATE_RULE";

    /**
     * @var string
     */
    protected $referenceName;

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
    public function __construct(ResultSet $resultSet) {
        $this->referenceName = $resultSet->getStringValue(AttributeMetaData::COLUMN_NAME);
        $this->foreignTable = $resultSet->getStringValue(self::REFERENCED_TABLE_NAME);
        $this->foreignColumn = $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME);
        $this->onDelete = $resultSet->getStringValue(self::DELETE_RULE);
        $this->onUpdate = $resultSet->getStringValue(self::UPDATE_RULE);
    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateReferenceInformation(ResultSet $resultSet) {
        $this->isNullAble = $resultSet->getStringValue(AttributeMetaData::COLUMN_IS_NULLABLE) === AttributeMetaData::COLUMN_IS_NULLABLE_YES;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->referenceName;
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        // TODO: Implement getRelationName() method.
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
        $this->onDelete;
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        $this->onUpdate;
    }

}