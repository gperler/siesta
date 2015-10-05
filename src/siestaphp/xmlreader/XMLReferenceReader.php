<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\naming\XMLAttribute;
use siestaphp\naming\XMLReference;

/**
 * Class XMLReferenceReader
 * @package siestaphp\datamodel\xml
 */
class XMLReferenceReader extends XMLAccess implements ReferenceSource
{
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
        return $this->getAttribute(XMLReference::ATTRIBUTE_ON_DELETE);
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return $this->getAttribute(XMLReference::ATTRIBUTE_ON_UPDATE);
    }

}