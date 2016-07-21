<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class TestColumnMetaData implements ColumnMetaData
{
    const DB_TYPE = "dbType";

    const DB_NAME = "dbName";

    const PHP_TYPE = "phpType";

    const IS_PRIMARY_KEY = "isPrimaryKey";

    const IS_REQUIRED = "isRequired";

    const AUTOVALUE = "autovalue";

    /**
     * @var array
     */
    protected $valueList;

    /**
     * TestColumnMetaData constructor.
     *
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;
    }

    /**
     * @return string
     */
    public function getDBType() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::DB_TYPE);
    }

    /**
     * @return string
     */
    public function getDBName() : string
    {
        return $this->dbName = ArrayUtil::getFromArray($this->valueList, self::DB_NAME);
    }

    /**
     * @return string
     */
    public function getPHPType() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::PHP_TYPE);
    }

    /**
     * @return bool
     */
    public function getIsRequired() : bool
    {
        return ArrayUtil::getFromArray($this->valueList, self::IS_REQUIRED) === true;
    }

    /**
     * @return bool
     */
    public function getIsPrimaryKey() : bool
    {
        return ArrayUtil::getFromArray($this->valueList, self::IS_PRIMARY_KEY) === true;
    }

    /**
     * @return null|string
     */
    public function getAutoValue()
    {
        return ArrayUtil::getFromArray($this->valueList, self::AUTOVALUE);
    }

}