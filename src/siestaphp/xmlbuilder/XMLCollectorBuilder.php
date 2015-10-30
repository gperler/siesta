<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\collector\CollectorGeneratorSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\naming\XMLCollector;

/**
 * Class XMLCollectorBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLCollectorBuilder extends XMLBuilder
{

    /**
     * @var CollectorSource
     */
    protected $collectorSource;

    /**
     * @param CollectorSource $collectorSource
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(CollectorSource $collectorSource, $domDocument, $parentElement)
    {
        parent::__construct($domDocument);

        $this->collectorSource = $collectorSource;

        $this->domElement = $this->createElement($parentElement, XMLCollector::ELEMENT_COLLECTOR_NAME);

        $this->addCollectorData();

        if ($collectorSource instanceof CollectorGeneratorSource) {
            $this->addCollectorTransformerData($collectorSource);
        }

    }

    /**
     * adds the standard information to the xml
     * @return void
     */
    private function addCollectorData()
    {
        $this->setAttribute(XMLCollector::ATTRIBUTE_NAME, $this->collectorSource->getName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_TYPE, $this->collectorSource->getType());
        $this->setAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CLASS, $this->collectorSource->getForeignClass());
        $this->setAttribute(XMLCollector::ATTRIBUTE_REFERENCE_NAME, $this->collectorSource->getReferenceName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_MAPPER_CLASS, $this->collectorSource->getMappingClass());
    }

    /**
     * adds additional information for transformation
     *
     * @param CollectorGeneratorSource $generatorSource
     *
     * @return void
     */
    private function addCollectorTransformerData(CollectorGeneratorSource $generatorSource)
    {
        $this->setAttribute(XMLCollector::ATTRIBUTE_METHOD_NAME, $generatorSource->getMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS, $generatorSource->getForeignConstructClass());
        $this->setAttribute(XMLCollector::ATTRIBUTE_REFERENCE_METHOD_NAME, $generatorSource->getReferenceMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_MAPPING_METHOD_NAME, $generatorSource->getNMMappingMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_FOREIGN_METHOD_NAME, $generatorSource->getNMForeignMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_THIS_METHOD_NAME, $generatorSource->getNMThisMethodName());


    }

}