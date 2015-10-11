<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\reference\MappingSource;

/**
 * Class ReferenceMappingMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class ReferenceMappingMetaData implements MappingSource
{

    protected $name;
    protected $foreignName;

    /**
     * @param string $name
     * @param string $foreignName
     */
    public function __construct($name, $foreignName)
    {
        $this->name = $name;
        $this->foreignName = $foreignName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getForeignName()
    {
        return $this->foreignName;
    }

}