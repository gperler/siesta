<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\reference\MappingSource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\naming\XMLMapping;
use siestaphp\naming\XMLReference;

/**
 * Class XMLReferenceReader
 * @package siestaphp\datamodel\xml
 */
class XMLReferenceReader extends XMLAccess implements ReferenceSource
{

    /**
     * @var MappingSource[]
     */
    protected $mappingList;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_NAME);
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_RELATION_NAME);
    }

    /**
     * @return string
     */
    public function getForeignClass()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_FOREIGN_CLASS);
    }

    /**
     * @return string
     */
    public function getForeignTable()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getForeignAttribute()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_FOREIGN_ATTRIBUTE);
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->getAttributeAsBool(XMLReference::ATTRIBUTE_REQUIRED);
    }

    /**
     * @return string
     */
    public function getOnDelete()
    {

        return strtolower($this->getAttribute(XMLReference::ATTRIBUTE_ON_DELETE));
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return strtolower($this->getAttribute(XMLReference::ATTRIBUTE_ON_UPDATE));
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->getAttributeAsBool(XMLReference::ATTRIBUTE_PRIMARY_KEY);
    }

    /**
     * @return string
     */
    public function getConstraintName()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_CONSTRAINT_NAME);
    }

    /**
     * @return MappingSource[]
     */
    public function getMappingSourceList()
    {
        if ($this->mappingList === null) {
            $this->readMappingList();
        }
        return $this->mappingList;

    }

    /**
     * @return void
     */
    private function readMappingList()
    {
        $this->mappingList = array();
        $mappingXMLList = $this->getXMLChildElementListByName(XMLMapping::ELEMENT_MAPPING_NAME);

        foreach ($mappingXMLList as $mappingXML) {
            $mapping = new XMLMappingReader();
            $mapping->setSource($mappingXML);
            $this->mappingList[] = $mapping;
        }

    }

    /**
     * @return ReferencedColumnSource[]
     */
    public function getReferencedColumnList()
    {
        return array();
    }

}