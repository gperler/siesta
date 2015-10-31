<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\naming\XMLCollector;
use siestaphp\naming\XMLCollectorFilter;

/**
 * Class XMLCollectorReader
 * @package siestaphp\xmlreader
 */
class XMLCollectorReader extends XMLAccess implements CollectorSource
{

    /**
     * @var CollectorFilterSource[]
     */
    protected $collectorFilterSourceList;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_NAME);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_TYPE);
    }

    /**
     * @return string
     */
    public function getForeignClass()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CLASS);
    }

    /**
     * @return string
     */
    public function getReferenceName()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_REFERENCE_NAME);
    }

    /**
     * @return string
     */
    public function getMappingClass()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_MAPPER_CLASS);
    }

    /**
     * @return CollectorFilterSource[]
     */
    public function getCollectorFilterSourceList()
    {
        if ($this->collectorFilterSourceList === null) {
            $this->readReferenceFilterSourceList();
        }

        return $this->collectorFilterSourceList;
    }

    /**
     * @return void
     */
    private function readReferenceFilterSourceList()
    {
        $this->collectorFilterSourceList = array();
        $collectorFilterXMLList = $this->getXMLChildElementListByName(XMLCollectorFilter::ELEMENT_FILTER_NAME);

        foreach ($collectorFilterXMLList as $collectorFilterXML) {
            $filter = new XMLCollectorFilterReader();
            $filter->setSource($collectorFilterXML);
            $this->collectorFilterSourceList[] = $filter;
        }

    }

}