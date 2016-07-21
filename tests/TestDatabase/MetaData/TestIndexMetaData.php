<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class TestIndexMetaData implements IndexMetaData
{

    const NAME = "name";

    const TYPE = "type";

    const IS_UNIQUE = "isUnique";

    const INDEX_PART_LIST = "indexPartList";

    /**
     * @var array
     */
    protected $valueList;

    /**
     * @var IndexPartMetaData[]
     */
    protected $indexPartList;

    /**
     * TestIndexMetaData constructor.
     *
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;
        $this->indexPartList = [];
        foreach (ArrayUtil::getFromArray($valueList, self::INDEX_PART_LIST) as $indexPart) {
            $this->indexPartList[] = new TestIndexPartMetaData($indexPart);
        }
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::NAME);
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::TYPE);
    }

    /**
     * @return bool
     */
    public function getIsUnique() : bool
    {
        return ArrayUtil::getFromArray($this->valueList, self::IS_UNIQUE);
    }

    /**
     * @return TestIndexPartMetaData[]
     */
    public function getIndexPartList() : array
    {
        return $this->indexPartList;
    }

}