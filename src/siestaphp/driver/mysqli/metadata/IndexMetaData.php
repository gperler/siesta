<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
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
     * @var AttributeMetaData[]
     */
    protected $attributeMetaDataList;

    /**
     * @var ReferenceMetaData[]
     */
    protected $referenceMetaDataList;

    /**
     * @var DatabaseColumn[]
     */
    protected $referencedColumnList;

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
     * @param AttributeMetaData[] $attributeMetaDataList
     * @param ReferenceMetaData[] $referenceMetaDataList
     */
    public function __construct(ResultSet $resultSet, $attributeMetaDataList, $referenceMetaDataList)
    {
        $this->referenceMetaDataList = $referenceMetaDataList;
        $this->attributeMetaDataList = $attributeMetaDataList;

        // list with parts (2 columns might be merged to one part)
        $this->indexPartList = [];

        // list of columns that are actually refered
        $this->referencedColumnList = [];

        // get standard information
        $this->indexName = $resultSet->getStringValue(self::INDEX_NAME);
        $this->unique = $resultSet->getIntegerValue(self::NON_UNIQUE) === 0;
        $this->type = $resultSet->getStringValue(self::INDEX_TYPE);

        // this will already be an index part .. others might come
        $this->addIndexPart($resultSet);
    }

    /**
     * adds an index part to this index.
     *
     * @param ResultSet $res
     */
    public function addIndexPart(ResultSet $res)
    {
        // get the column name
        $columnName = IndexPartMetaData::getColumnNameFromResultSet($res);

        // this might be the name of a reference (and several column will be merged to this one)
        $referencedName = $this->getReferencedName($columnName);

        // if this indexpart has already been found, done
        if (isset($this->indexPartList[$referencedName])) {
            return;
        }

        // create a new index part (refering a reference)
        $this->indexPartList[$referencedName] = new IndexPartMetaData($columnName, $res);

        // get list of columns that this index references
        $this->referencedColumnList = array_merge($this->referencedColumnList, $this->getReferencedDatabaseColumnList($columnName));
    }

    /**
     * @param $columnName
     *
     * @return string
     */
    private function getReferencedName($columnName)
    {
        // if the index references an attribute its name can be used
        foreach ($this->attributeMetaDataList as $attribute) {
            if ($attribute->getDatabaseName() === $columnName) {
                return $columnName;
            }
        }

        // if the index references a column of a reference, use the reference name
        foreach ($this->referenceMetaDataList as $reference) {
            if ($reference->refersColumnName($columnName)) {
                return $reference->getName();
            }
        }
    }

    /**
     * @param $columnName
     *
     * @return ReferencedColumnSource[]
     */
    private function getReferencedDatabaseColumnList($columnName)
    {
        // if the index references an attribute return it
        foreach ($this->attributeMetaDataList as $attribute) {
            if ($attribute->getDatabaseName() === $columnName) {
                return [$attribute];
            }
        }

        // if the index references a reference (might have several columns) return list of referenced columns
        foreach ($this->referenceMetaDataList as $reference) {
            if ($reference->refersColumnName($columnName)) {
                return $reference->getReferencedColumnList();
            }
        }
    }

    /**
     * @return string
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
        return strtoupper($this->type);
    }

    /**
     * @return IndexPartSource[]
     */
    public function getIndexPartSourceList()
    {
        return array_values($this->indexPartList);
    }

    /**
     * @return DatabaseColumn[]
     */
    public function getReferencedColumnList()
    {
        return $this->referencedColumnList;
    }

}