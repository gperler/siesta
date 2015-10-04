<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.06.15
 * Time: 20:11
 */

namespace siestaphp\xmlreader;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\naming\XMLAttribute;


/**
 * Class XMLAttribute
 * @package siestaphp\datamodel\xml
 */
class XMLAttributeReader extends XMLAccess implements AttributeSource {



    /**
     * @return string
     */
    public function getName()
    {
       return $this->getAttribute(XMLAttribute::ATTRIBUTE_NAME);
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return $this->getAttribute(XMLAttribute::ATTRIBUTE_TYPE);
    }

    /**
     * @return string
     */
    public function getAutoValue()
    {
        return $this->getAttribute(XMLAttribute::ATTRIBUTE_AUTO_VALUE);
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {

        return strtoupper($this->getAttribute(XMLAttribute::ATTRIBUTE_DATABASE_NAME));
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return strtoupper($this->getAttribute(XMLAttribute::ATTRIBUTE_DATABASE_TYPE));
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->getAttributeAsBool(XMLAttribute::ATTRIBUTE_PRIMARY_KEY);
    }

    /**
     * @return bool
     */
    public function addIndex()
    {
        return $this->getAttributeAsBool(XMLAttribute::ATTRIBUTE_ADD_INDEX);
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->getAttributeAsBool(XMLAttribute::ATTRIBUTE_REQUIRED);
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->getAttribute(XMLAttribute::ATTRIBUTE_DEFAULT_VALUE);
    }

}