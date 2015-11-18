<?php

namespace siestaphp\tests\functional\constructfactory\ns1;

use siestaphp\tests\functional\constructfactory\gen\ns3\ArtistEntity;

/**
 * Class Artist
 * @package siestaphp\tests\functional\constructfactory\ns1
 */
class Artist extends ArtistEntity
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