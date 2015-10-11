<?php

namespace siestaphp\datamodel\reference;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\Attribute;
use siestaphp\datamodel\DatabaseColumn;

/**
 * Class ReferencedColumn
 * @package siestaphp\datamodel
 */
class ReferencedColumn implements DatabaseColumn, ReferencedColumnSource
{

    /**
     * @var DatabaseColumn
     */
    protected $attributeSource;

    /**
     * @var ReferenceSource
     */
    protected $referenceSource;

    /**
     * @var MappingSource
     */
    protected $mappingSource;

    /**
     * @param DatabaseColumn $ats
     * @param ReferenceSource $referenceSource
     * @param MappingSource $mappingSource
     */
    public function fromAttributeSource(DatabaseColumn $ats, ReferenceSource $referenceSource, $mappingSource)
    {
        $this->attributeSource = $ats;
        $this->referenceSource = $referenceSource;
        $this->mappingSource = $mappingSource;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->mappingSource !== null) {
            return $this->mappingSource->getName();
        }
        return $this->referenceSource->getName() . ucfirst($this->attributeSource->getName());
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->getName());
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return $this->attributeSource->getPHPType();
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        if ($this->mappingSource) {
            return $this->mappingSource->getDatabaseName();
        }
        return strtoupper($this->referenceSource->getName()) . "_" . $this->attributeSource->getDatabaseName();
    }

    /**
     * @return string
     */
    public function getReferencedColumnName()
    {
        return $this->attributeSource->getName();
    }

    /**
     * @return string
     */
    public function getReferencedDatabaseName()
    {
        return $this->attributeSource->getDatabaseName();
    }

    /**
     * @return string
     */
    public function getReferencedColumnMethodName()
    {
        return $this->attributeSource->getMethodName();
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->attributeSource->getDatabaseType();
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->referenceSource->isPrimaryKey();
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->referenceSource->isRequired();
    }

    /**
     * @return string
     */
    public function getSQLParameterName()
    {
        if ($this->mappingSource !== null) {
            return strtoupper(Attribute::PARAMETER_PREFIX . $this->getName());
        }
        return strtoupper(Attribute::PARAMETER_PREFIX . $this->referenceSource->getName() . "_" . $this->attributeSource->getName());

    }

}