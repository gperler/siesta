<?php


namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\reference\ReferenceTransformerSource;
use siestaphp\naming\XMLReference;

/**
 * Class XMLAttributeBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLReferenceBuilder extends XMLBuilder
{

    /**
     * @var ReferenceSource
     */
    protected $referenceSource;

    /**
     * @param ReferenceSource $referenceSource
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(ReferenceSource $referenceSource, \DOMDocument $domDocument, \DOMElement $parentElement)
    {
        parent::__construct($domDocument);

        $this->referenceSource = $referenceSource;

        $this->domElement = $this->createElement($parentElement, XMLReference::ELEMENT_REFERENCE_NAME);

        $this->addReferenceData();

        if ($referenceSource instanceof ReferenceTransformerSource) {
            $this->addGeneratorData($referenceSource);
        }
    }

    /**
     *
     */
    protected function addReferenceData()
    {
        $this->setAttribute(XMLReference::ATTRIBUTE_NAME, $this->referenceSource->getName());
        $this->setAttribute(XMLReference::ATTRIBUTE_FOREIGN_CLASS, $this->referenceSource->getForeignClass());
        $this->setAttributeAsBool(XMLReference::ATTRIBUTE_REQUIRED, $this->referenceSource->isRequired());
    }

    /**
     * adds information about the reference for transformation including referenced columns
     *
     * @param ReferenceTransformerSource $referenceTransformerSource
     */
    protected function addGeneratorData(ReferenceTransformerSource $referenceTransformerSource)
    {

        // add derived data
        $this->setAttribute(XMLReference::ATTRIBUTE_METHOD_NAME, $referenceTransformerSource->getMethodName());
        $this->setAttribute(XMLReference::ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS, $referenceTransformerSource->getReferencedConstructClass());
        $this->setAttribute(XMLReference::ATTRIBUTE_SP_FINDER_NAME, $referenceTransformerSource->getStoredProcedureFinderName());
        $this->setAttribute(XMLReference::ATTRIBUTE_SP_DELETER_NAME, $referenceTransformerSource->getStoredProcedureDeleterName());
        $this->setAttribute(XMLReference::ATTRIBUTE_FOREIGN_METHOD_NAME, $referenceTransformerSource->getForeignMethodName());
        $this->setAttributeAsBool(XMLReference::ATTRIBUTE_SP_REFERENCE_CREATOR_NEEDED, $referenceTransformerSource->isReferenceCreatorNeeded());

        // create xml element for referenced columns
        $xmlReferencedColumnList = $this->createElement($this->domElement, XMLReference::ELEMENT_REFERENCED_COLUMN_LIST);

        // attach referenced columns to xml
        $referencedColumnList = $referenceTransformerSource->getReferenceColumnList();
        foreach ($referencedColumnList as $referencedColumn) {
            $this->addReferencedColumn($xmlReferencedColumnList, $referencedColumn);
        }

    }

    /**
     * a referenced column refers to a primary key attribute in the referenced entity
     *
     * @param \DOMElement $xmlReferencedColumnList
     * @param ReferencedColumnSource $referencedColumn
     */
    protected function addReferencedColumn(\DOMElement $xmlReferencedColumnList, ReferencedColumnSource $referencedColumn)
    {
        // create element
        $xmlReferencedColumn = $this->createElement($xmlReferencedColumnList, XMLReference::ELEMENT_COLUMN);

        // add data
        $xmlReferencedColumn->setAttribute(XMLReference::ATTRIBUTE_COLUMN_NAME, $referencedColumn->getName());
        $xmlReferencedColumn->setAttribute(XMLReference::ATTRIBUTE_COLUMN_PHPTYPE, $referencedColumn->getPHPType());
        $xmlReferencedColumn->setAttribute(XMLReference::ATTRIBUTE_COLUMN_METHODNAME, $referencedColumn->getMethodName());
        $xmlReferencedColumn->setAttribute(XMLReference::ATTRIBUTE_COLUMN_DATABASE_NAME, $referencedColumn->getDatabaseName());
    }

}