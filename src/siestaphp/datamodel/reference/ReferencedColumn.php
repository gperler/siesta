<?php

namespace siestaphp\datamodel\reference;

use siestaphp\datamodel\attribute\Attribute;
use siestaphp\datamodel\DatabaseColumn;

/**
 * Class ReferencedColumn
 * @package siestaphp\datamodel
 */
class ReferencedColumn implements DatabaseColumn, ReferencedColumnSource {


    /**
     * @var DatabaseColumn
     */
    protected $attributeSource;


    /**
     * @var ReferenceSource
     */
    protected $referenceSource;

    /**
     * @param DatabaseColumn $ats
     * @param ReferenceSource $referenceSource
     */
    public function fromAttributeSource(DatabaseColumn $ats, ReferenceSource $referenceSource) {
        $this->attributeSource = $ats;
        $this->referenceSource = $referenceSource;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->attributeSource->getName();
    }

    /**
     * @return string
     */
    public function getMethodName() {
        return ucfirst($this->attributeSource->getName());
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
        return "FK_" . strtoupper($this->referenceSource->getName()) . "_" . $this->attributeSource->getDatabaseName();
    }

    /**
     * @return string
     */
    public function getReferencedColumnName() {
        return $this->attributeSource->getDatabaseName();
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
        return false;
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
        return strtoupper(Attribute::PARAMETER_PREFIX . $this->referenceSource->getName() . "_" . $this->attributeSource->getName());
    }

}