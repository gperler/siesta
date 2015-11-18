<?php

namespace siestaphp\tests\functional\constructfactory\ns2;

use siestaphp\tests\functional\constructfactory\gen\ns4\LabelEntity;

/**
 * Class Label
 * @package siestaphp\tests\functional\constructfactory\ns2
 */
class Label extends LabelEntity
{

    /**
     * @var bool
     */
    protected $testValue;

    /**
     * @param bool $testValue
     */
    public function __construct($testValue)
    {
        $this->testValue = $testValue;
    }

    /**
     * @return bool
     */
    public function getTestValue()
    {
        return $this->testValue;
    }

}