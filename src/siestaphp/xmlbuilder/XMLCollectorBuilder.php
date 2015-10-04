<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.09.15
 * Time: 12:39
 */

namespace siestaphp\xmlbuilder;



use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\collector\CollectorTransformerSource;
use siestaphp\naming\XMLCollector;

/**
 * Class XMLCollectorBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLCollectorBuilder extends XMLBuilder {


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

        if ($collectorSource instanceof CollectorTransformerSource) {
            $this->addCollectorTransformerData($collectorSource);
        }


    }

    /**
     * adds the standard information to the xml
     */
    private function addCollectorData() {
        $this->setAttribute(XMLCollector::ATTRIBUTE_NAME, $this->collectorSource->getName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_TYPE, $this->collectorSource->getType());
        $this->setAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CLASS, $this->collectorSource->getForeignClass());
        $this->setAttribute(XMLCollector::ATTRIBUTE_REFERENCE_NAME, $this->collectorSource->getReferenceName());
    }


    /**
     * adds additional information for transformation
     * @param CollectorTransformerSource $transformerSource
     */
    private function addCollectorTransformerData(CollectorTransformerSource $transformerSource) {
        $this->setAttribute(XMLCollector::ATTRIBUTE_METHOD_NAME, $transformerSource->getMethodName());
        $this->setAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS, $transformerSource->getForeignConstructClass());
        $this->setAttribute(XMLCollector::ATTRIBUTE_REFERENCE_METHOD_NAME, $transformerSource->getReferenceMethodName());
    }

}