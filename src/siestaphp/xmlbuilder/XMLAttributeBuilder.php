<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\naming\XMLAttribute;

/**
 * Class XMLAttributeBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLAttributeBuilder extends XMLBuilder
{

    /**
     * @var AttributeSource
     */
    protected $attributeSource;

    /**
     * @param AttributeSource $attributeSource
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(AttributeSource $attributeSource, $domDocument, $parentElement)
    {
        parent::__construct($domDocument);

        $this->attributeSource = $attributeSource;

        $this->domElement = $this->createElement($parentElement, XMLAttribute::ELEMENT_ATTRIBUTE_NAME);

        $this->addAttributeData();

        // add extended information for transformer
        if ($attributeSource instanceof AttributeGeneratorSource) {
            $this->addGeneratorValues($attributeSource);
        }

    }

    /**
     * adds the default values for attributes
     * @return void
     */

    private function addAttributeData()
    {
        $this->setAttribute(XMLAttribute::ATTRIBUTE_NAME, $this->attributeSource->getName());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_TYPE, $this->attributeSource->getPHPType());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_DATABASE_NAME, $this->attributeSource->getDatabaseName());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_DATABASE_TYPE, $this->attributeSource->getDatabaseType());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_DEFAULT_VALUE, $this->attributeSource->getDefaultValue());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_AUTO_VALUE, $this->attributeSource->getAutoValue());
        $this->setAttributeAsBool(XMLAttribute::ATTRIBUTE_PRIMARY_KEY, $this->attributeSource->isPrimaryKey());
        $this->setAttributeAsBool(XMLAttribute::ATTRIBUTE_REQUIRED, $this->attributeSource->isRequired());
        $this->setAttributeAsBool(XMLAttribute::ATTRIBUTE_TRANSIENT, $this->attributeSource->isTransient());

    }

    /**
     * adds additional values for transformation
     *
     * @param AttributeGeneratorSource $source
     *
     * @return void
     */
    private function addGeneratorValues(AttributeGeneratorSource $source)
    {
        $this->setAttribute(XMLAttribute::ATTRIBUTE_METHOD_NAME, $source->getMethodName());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_DATABASE_LENGTH, $source->getLength());
        $this->setAttribute(XMLAttribute::ATTRIBUTE_CONST_DB_NAME, strtoupper($source->getName()));
    }

}