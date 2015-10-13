<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\driver\ResultSet;

/**
 * Class IndexMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class IndexMetaData implements IndexSource
{

    const PRIMARY_KEY_INDEX_NAME = "PRIMARY";

    const INDEX_NAME = "INDEX_NAME";

    const NON_UNIQUE = "NON_UNIQUE";

    const SEQ_IN_INDEX = "SEQ_IN_INDEX";

    const NULLABLE = "NULLABLE";

    const NULLABLE_YES = "YES";

    const INDEX_TYPE = "INDEX_TYPE";

    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var bool
     */
    protected $unique;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var IndexPartMetaData[]
     */
    protected $indexPartList;

    /**
     * @param ResultSet $resultSet
     *
     * @return bool
     */
    public static function isValidIndex(ResultSet $resultSet)
    {
        return self::getIndexNameFromResultSet($resultSet) !== self::PRIMARY_KEY_INDEX_NAME;
    }

    /**
     * @param ResultSet $resultSet
     *
     * @return string
     */
    public static function getIndexNameFromResultSet(ResultSet $resultSet)
    {
        return $resultSet->getStringValue(self::INDEX_NAME);
    }

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->indexName = $resultSet->getStringValue(self::INDEX_NAME);
        $this->unique = $resultSet->getIntegerValue(self::NON_UNIQUE) === 0;
        $this->type = $resultSet->getStringValue(self::INDEX_TYPE);
        $this->indexPartList = array();

        // this will already be an index part .. others might come
        $this->addIndexPart($resultSet);
    }

    /**
     * @param ResultSet $res
     */
    public function addIndexPart(ResultSet $res)
    {
        $sequence = $res->getIntegerValue(self::SEQ_IN_INDEX);
        $this->indexPartList[$sequence] = new IndexPartMetaData($res);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->indexName;
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return IndexPartSource[]
     */
    public function getIndexPartSourceList()
    {
       return $this->indexPartList;
    }

}