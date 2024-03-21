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
    protected string $columnName;

    /**
     * @var int|null
     */
    protected ?int $length;

    /**
     * @param ResultSet $resultSet
     */
    public function fromResultSet(ResultSet $resultSet): void
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
    public function getLength(): ?int
    {
        return $this->length;
    }

}