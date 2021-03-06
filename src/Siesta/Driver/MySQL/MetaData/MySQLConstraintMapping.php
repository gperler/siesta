<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\Database\ResultSet;

/**
 * @author Gregor Müller
 */
class MySQLConstraintMapping implements ConstraintMappingMetaData
{

    const COLUMN_NAME = "COLUMN_NAME";

    const REFERENCED_COLUMN_NAME = "REFERENCED_COLUMN_NAME";

    /**
     * @var string
     */
    protected $localColumn;

    /**
     * @var string
     */
    protected $foreignColumn;

    /**
     * @param ResultSet $resultSet
     */
    public function fromResultSet(ResultSet $resultSet)
    {
        $this->localColumn = $resultSet->getStringValue(self::COLUMN_NAME);
        $this->foreignColumn = $resultSet->getStringValue(self::REFERENCED_COLUMN_NAME);
    }

    /**
     * @return string
     */
    public function getForeignColumn() : string
    {
        return $this->foreignColumn;
    }

    /**
     * @return string
     */
    public function getLocalColumn() : string
    {
        return $this->localColumn;
    }

}