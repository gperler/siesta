<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\naming\XMLIndex;
use siestaphp\naming\XMLIndexPart;

/**
 * Class XMLIndexBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLIndexBuilder extends XMLBuilder
{

    protected $indexSource;

    /**
     * @param IndexSource $indexSource
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(IndexSource $indexSource, \DOMDocument $domDocument, \DOMElement $parentElement)
    {

        parent::__construct($domDocument);

        $this->indexSource = $indexSource;

        $this->domElement = $this->createElement($parentElement, XMLIndex::ELEMENT_INDEX_NAME);

        $this->addIndexData();

        $this->addIndexPartListData();

    }

    private function addIndexData()
    {
        $this->setAttribute(XMLIndex::ATTRIBUTE_NAME, $this->indexSource->getName());
        $this->setAttribute(XMLIndex::ATTRIBUTE_TYPE, $this->indexSource->getType());
        $this->setAttributeAsBool(XMLIndex::ATTRIBUTE_UNIQUE, $this->indexSource->isUnique());

    }

    private function addIndexPartListData()
    {

        foreach ($this->indexSource->getIndexPartSourceList() as $indexPartSource) {
            $this->addIndexPart($this->domElement, $indexPartSource);
        }

    }

    /**
     * @param \DOMElement $parentElement
     * @param IndexPartSource $indexPartSource
     */
    private function addIndexPart(\DOMElement $parentElement, IndexPartSource $indexPartSource)
    {
        $xmlIndexPart = $this->createElement($parentElement, XMLIndexPart::ELEMENT_INDEX_PART_NAME);
        $xmlIndexPart->setAttribute(XMLIndexPart::ATTRIBUTE_NAME, $indexPartSource->getName());
        $xmlIndexPart->setAttribute(XMLIndexPart::ATTRIBUTE_LENGTH, $indexPartSource->getLength());
        $xmlIndexPart->setAttribute(XMLIndexPart::ATTRIBUTE_SORT_ORDER, $indexPartSource->getSortOrder());
    }
}