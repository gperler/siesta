<?php

namespace siestaphp\datamodel\delimit;

use siestaphp\datamodel\attribute\Attribute;
use siestaphp\datamodel\attribute\AttributeGeneratorSource;

/**
 * Class DelimitAttribute
 * @package siestaphp\datamodel\delimit
 */
class DelimitAttribute implements AttributeGeneratorSource
{

    /**
     * @return DelimitAttribute[]
     */
    public static function getDelimitAttributes()
    {
        return array(
            new DelimitAttribute("_delimit_id", "string", "_delimit_id", "VARCHAR(36)", "uuid", true, "UUID()"),
            new DelimitAttribute("_validFrom", "DateTime", "_validFrom", "DATETIME", null, false, "NOW()"),
            new DelimitAttribute("_validUntil", "DateTime", "_validUntil", "DATETIME", null, false, "NULL"),
            new DelimitAttribute("_changedBy", "string", "_changedBy", "VARCHAR(36)", null, false, "NULL"));
    }

    protected $name;
    protected $phpType;
    protected $databaseType;
    protected $autoValue;
    protected $isPrimaryKey;
    protected $insertDefault;

    /**
     * @param string $name
     * @param string $phpType
     * @param string $databaseName
     * @param string $databaseType
     * @param string $autoValue
     * @param bool $isPrimaryKey
     * @param string $insertDefault
     */
    public function __construct($name, $phpType, $databaseName, $databaseType, $autoValue, $isPrimaryKey, $insertDefault)
    {
        $this->name = $name;
        $this->phpType = $phpType;
        $this->getDatabaseName = $databaseName;
        $this->databaseType = $databaseType;
        $this->autoValue = $autoValue;
        $this->isPrimaryKey = $isPrimaryKey;
        $this->insertDefault = $insertDefault;
    }

    /**
     * @return string
     */
    public function getInsertDefault() {
        return $this->insertDefault;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->name);
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getSQLParameterName()
    {
        return Attribute::PARAMETER_PREFIX . $this->name;
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
    public function getPHPType()
    {
        return $this->phpType;
    }

    /**
     * @return string
     */
    public function getAutoValue()
    {
        return $this->autoValue;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->getDatabaseName;
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->databaseType;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isTransient()
    {
        return false;
    }

}