<?php

namespace siestaphp\xmlreader;

use Codeception\Util\Debug;
use siestaphp\datamodel\reference\MappingSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\naming\XMLAttribute;
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
     * @return mixed
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
     * @return mixed
     */
    public function getForeignClass()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_FOREIGN_CLASS);
    }

    /**
     * @return mixed
     */
    public function getForeignAttribute()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_FOREIGN_ATTRIBUTE);
    }

    /**
     * @return mixed
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

}