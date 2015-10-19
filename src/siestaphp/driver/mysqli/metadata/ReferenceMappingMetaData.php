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

    protected $databaseType;

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

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->databaseType;
    }

    /**
     * @param string $databaseType
     */
    public function setDatabaseType($databaseType)
    {
        $this->databaseType = $databaseType;
    }
}