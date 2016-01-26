<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\collector\CollectorGeneratorSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\naming\XMLCollector;
use siestaphp\naming\XMLCollectorFilter;
use siestaphp\naming\XMLStoredProcedure;

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
            $this->addCollectorGeneratorData($collectorSource);
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
    private function addCollectorGeneratorData(CollectorGeneratorSource $generatorSource)
    {
        $this->setAttribute(XMLCollector::ATTRIBUTE_METHOD_NAME, $generatorSource->getMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS, $generatorSource->getForeignConstructClass());
        $this->setAttribute(XMLCollector::ATTRIBUTE_REFERENCE_METHOD_NAME, $generatorSource->getReferenceMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_MAPPING_METHOD_NAME, $generatorSource->getNMMappingMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_FOREIGN_METHOD_NAME, $generatorSource->getNMForeignMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_THIS_METHOD_NAME, $generatorSource->getNMThisMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_NM_DELETE_MAPPING_SP_NAME,$generatorSource->getNMDeleteStoredProcedueName() );

        $this->addForeignEntityManagerData($generatorSource);
        $this->addForeignConstructData($generatorSource);

        foreach ($generatorSource->getCollectorFilterSourceList() as $filter) {
            $this->addCollectorFilter($filter);
        }

    }

    /**
     * @param CollectorGeneratorSource $generatorSource
     */
    protected function addForeignEntityManagerData(CollectorGeneratorSource $generatorSource)
    {
        $referencedEntity = $generatorSource->getReferencedEntity();
        new XMLEntityManagerBuilder($referencedEntity->getEntityManagerSource(), $this->domDocument, $this->domElement);
    }

    /**
     * @param CollectorGeneratorSource $generatorSource
     */
    protected function addForeignConstructData(CollectorGeneratorSource $generatorSource)
    {
        $referencedEntity = $generatorSource->getReferencedEntity();
        new XMLConstructBuilder($referencedEntity->getConstructSource(), $this->domDocument, $this->domElement);
    }

    /**
     * @param CollectorFilterSource $filterSource
     */
    protected function addCollectorFilter(CollectorFilterSource $filterSource)
    {
        $xmlReferenceFilter = $this->createElement($this->domElement, XMLCollectorFilter::ELEMENT_FILTER_NAME);
        $xmlReferenceFilter->setAttribute(XMLCollectorFilter::ATTRIBUTE_FILTER_NAME, $filterSource->getName());
        $xmlReferenceFilter->setAttribute(XMLCollectorFilter::ATTRIBUTE_FILTER_FILTER, $filterSource->getFilter());

        foreach ($filterSource->getSPParameterList() as $parameter) {
            $this->addParameter($xmlReferenceFilter, $parameter);
        }
    }

    /**
     * adds information about a parameter (name, type)
     *
     * @param \DOMElement $parent
     * @param SPParameterSource $source
     *
     * @return void
     */
    private function addParameter(\DOMElement $parent, SPParameterSource $source)
    {
        // create xml container
        $xmlParameter = $this->createElement($parent, XMLStoredProcedure::ELEMENT_PARAMETER);

        // add attributes
        $xmlParameter->setAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_NAME, $source->getName());
        $xmlParameter->setAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_TYPE, $source->getPHPType());
    }

}