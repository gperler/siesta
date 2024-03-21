<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class TestIndexPartMetaData implements IndexPartMetaData
{
    const COLUMN_NAME = "columnName";

    const SORT_ORDER = "sortOrder";

    const LENGTH = "length";

    /**
     * @var array
     */
    protected $valueList;

    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;
    }

    /**
     * @return string
     */
    public function getColumnName() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::COLUMN_NAME);
    }

    /**
     * @return string
     */
    public function getSortOrder() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::SORT_ORDER);
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return ArrayUtil::getFromArray($this->valueList, self::LENGTH);
    }

}