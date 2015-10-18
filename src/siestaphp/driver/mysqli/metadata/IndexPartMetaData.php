<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\driver\ResultSet;

/**
 * Class IndexPartMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class IndexPartMetaData implements IndexPartSource
{

    const COLUMN_NAME = "COLUMN_NAME";

    const SUB_PART = "SUB_PART";

    /**
     * @param ResultSet $resultSet
     *
     * @return string
     */
    public static function getColumnName(ResultSet $resultSet)
    {
        return $resultSet->getStringValue(self::COLUMN_NAME);
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $length;

    /**
     * @param string $referencedName
     * @param ResultSet $resultSet
     */
    public function __construct($referencedName, ResultSet $resultSet)
    {
        $this->name = $referencedName;
        $this->length = $resultSet->getIntegerValue(self::SUB_PART);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return "ASC";
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

}