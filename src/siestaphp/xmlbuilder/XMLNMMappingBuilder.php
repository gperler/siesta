<?php

namespace siestaphp\xmlbuilder;


use siestaphp\datamodel\collector\NMMapping;
use siestaphp\datamodel\collector\NMMappingSource;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\naming\XMLMapping;
use siestaphp\naming\XMLNMMapping;

/**
 * Class XMLNMMappingBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLNMMappingBuilder extends XMLBuilder
{

    /**
     * @var NMMappingSource
     */
    protected $nmMapping;

    /**
     * @param NMMappingSource $mappingSource
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(NMMappingSource $mappingSource, $domDocument, $parentElement)
    {
        parent::__construct($domDocument);

        $this->nmMapping = $mappingSource;

        $this->domElement = $this->createElement($parentElement, XMLNMMapping::ELEMENT_NAME);

        $this->addMappingData();
    }


    protected function addMappingData() {
        $this->setAttribute(XMLNMMapping::ATTRIBUTE_NAME, $this->nmMapping->getPHPMethodName());
        $this->setAttribute(XMLNMMapping::ATTRIBUTE_SP_NAME, $this->nmMapping->getStoredProcedureName());

        foreach($this->nmMapping->getForeignPrimaryKeyColumnList() as $pkColumn) {
            $this->addPKColum($pkColumn);
        }
    }

    /**
     * @param DatabaseColumn $pkColumn
     */
    protected function addPKColum(DatabaseColumn $pkColumn) {
        $pkColumXML = $this->createElement($this->domElement, XMLNMMapping::ELEMENT_PK_COLUMN);
        $pkColumXML->setAttribute(XMLNMMapping::ATTRIBUTE_PK_COLUMN_NAME, $pkColumn->getName());
        $pkColumXML->setAttribute(XMLNMMapping::ATTRIBUTE_PK_PHP_TYPE, $pkColumn->getPHPType());

    }


}