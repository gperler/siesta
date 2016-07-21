<?php

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class TestConstraintMetaData implements ConstraintMetaData
{
    const NAME = "name";

    const CONSTRAINT_NAME = "constraintName";

    const FOREIGN_TABLE = "foreignTable";

    const ON_UPDATE = "onUpdate";

    const ON_DELETE = "onDelete";

    const CONSTRAINT_MAPPING_LIST = "constraintMappingList";

    /**
     * @var array
     */
    protected $valueList;

    /**
     * @var ConstraintMappingMetaData[]
     */
    protected $constraintMappingList;

    /**
     * TestConstraintMetaData constructor.
     *
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;
        $this->constraintMappingList = [];
        foreach (ArrayUtil::getFromArray($this->valueList, self::CONSTRAINT_MAPPING_LIST) as $constraintMapping) {
            $this->constraintMappingList[] = new TestConstraintMappingMetaData($constraintMapping);
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
    public function getConstraintName() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::CONSTRAINT_NAME);
    }

    /**
     * @return string
     */
    public function getForeignTable() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::FOREIGN_TABLE);
    }

    /**
     * @return string
     */
    public function getOnUpdate() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::ON_UPDATE);

    }

    /**
     * @return string
     */
    public function getOnDelete() : string
    {
        return ArrayUtil::getFromArray($this->valueList, self::ON_DELETE);
    }

    /**
     * @return TestConstraintMappingMetaData[]
     */
    public function getConstraintMappingList() : array
    {
        return $this->constraintMappingList;
    }

}