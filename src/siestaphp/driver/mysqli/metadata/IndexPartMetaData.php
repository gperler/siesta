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
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $length;

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->name = $resultSet->getStringValue(self::COLUMN_NAME);
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