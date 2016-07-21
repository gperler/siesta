<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class TestConstraintMappingMetaData implements ConstraintMappingMetaData
{
    const FOREIGN_COLUMN = "foreignColumn";

    const LOCAL_COLUMN = "localColumn";

    /**
     * @var array
     */
    protected $valueList;

    /**
     * TestConstraintMappingMetaData constructor.
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
    public function getForeignColumn() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::FOREIGN_COLUMN);
    }

    /**
     * @return string
     */
    public function getLocalColumn() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::LOCAL_COLUMN);
    }

}