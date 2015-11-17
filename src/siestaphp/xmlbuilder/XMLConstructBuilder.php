<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\construct\ConstructSource;
use siestaphp\naming\XMLConstruct;

/**
 * Class XMLCollectorBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLConstructBuilder extends XMLBuilder
{

    protected $constructSource;

    /**
     * @param ConstructSource $constructorSource
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(ConstructSource $constructorSource, $domDocument, $parentElement)
    {
        parent::__construct($domDocument);

        $this->constructSource = $constructorSource;

        $this->domElement = $this->createElement($parentElement, XMLConstruct::ELEMENT_CONSTRUCT_NAME);

        $this->addConstructData();
    }

    /**
     * adds the standard information to the xml
     * @return void
     */
    private function addConstructData()
    {
        $this->setAttribute(XMLConstruct::ATTRIBUTE_CLASS_NAME, $this->constructSource->getConstructorClass());
        $this->setAttribute(XMLConstruct::ATTRIBUTE_CLASS_NAMESPACE, $this->constructSource->getConstructorNamespace());
        $this->setAttribute(XMLConstruct::ATTRIBUTE_CONSTRUCT_FACTORY, $this->constructSource->getConstructFactory());
        $this->setAttribute(XMLConstruct::ATTRIBUTE_CONSTRUCT_FACTORY_FQN, $this->constructSource->getConstructFactoryFqn());
    }

}