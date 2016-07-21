<?php
declare(strict_types=1);
namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\Database\ResultSet;

/**
 * @author Gregor Müller
 */
class MySQLIndexPart implements IndexPartMetaData
{

    const COLUMN_NAME = "COLUMN_NAME";

    const SUB_PART = "SUB_PART";

    /**
     * @var string
     */
    protected $columnName;

    /**
     * @var int
     */
    protected $length;

    /**
     * @param ResultSet $resultSet
     */
    public function fromResultSet(ResultSet $resultSet)
    {
        $this->columnName = $resultSet->getStringValue(self::COLUMN_NAME);
        $this->length = $resultSet->getIntegerValue(self::SUB_PART);
    }

    /**
     * @return string
     */
    public function getColumnName() : string
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getSortOrder() : string
    {
        return "ASC";
    }

    /**
     * @return int|null
     */
    public function getLength()
    {
        return $this->length;
    }

}