<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\naming\XMLAttribute;
use siestaphp\naming\XMLCollectorFilter;
use siestaphp\naming\XMLMapping;
use siestaphp\naming\XMLReference;
use siestaphp\naming\XMLStoredProcedure;

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
        $this->addMappingData();

        if ($referenceSource instanceof ReferenceGeneratorSource) {
            $this->addGeneratorData($referenceSource);
        }
    }

    /**
     * @return void
     */
    protected function addReferenceData()
    {
        $this->setAttribute(XMLReference::ATTRIBUTE_NAME, $this->referenceSource->getName());
        $this->setAttribute(XMLReference::ATTRIBUTE_FOREIGN_CLASS, $this->referenceSource->getForeignClass());
        $this->setAttributeAsBool(XMLReference::ATTRIBUTE_REQUIRED, $this->referenceSource->isRequired());
        $this->setAttribute(XMLReference::ATTRIBUTE_ON_DELETE, $this->referenceSource->getOnDelete());
        $this->setAttribute(XMLReference::ATTRIBUTE_ON_UPDATE, $this->referenceSource->getOnUpdate());
        $this->setAttributeAsBool(XMLReference::ATTRIBUTE_PRIMARY_KEY, $this->referenceSource->isPrimaryKey());
        $this->setAttribute(XMLReference::ATTRIBUTE_CONSTRAINT_NAME, $this->referenceSource->getConstraintName());

    }

    /**
     * @return void
     */
    protected function addMappingData()
    {

        foreach ($this->referenceSource->getMappingSourceList() as $mapping) {
            $mappingXML = $this->createElement($this->domElement, XMLMapping::ELEMENT_MAPPING_NAME);
            $mappingXML->setAttribute(XMLMapping::ATTRIBUTE_NAME, $mapping->getName());
            $mappingXML->setAttribute(XMLMapping::ATTRIBUTE_DATABASE_NAME, $mapping->getDatabaseName());
            $mappingXML->setAttribute(XMLMapping::ATTRIBUTE_FOREIGN_NAME, $mapping->getForeignName());
        }

    }

    /**
     * adds information about the reference for transformation including referenced columns
     *
     * @param ReferenceGeneratorSource $referenceGeneratorSource
     *
     * @return void
     */
    protected function addGeneratorData(ReferenceGeneratorSource $referenceGeneratorSource)
    {

        // add derived data
        $this->setAttribute(XMLReference::ATTRIBUTE_METHOD_NAME, $referenceGeneratorSource->getMethodName());
        $this->setAttribute(XMLReference::ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS, $referenceGeneratorSource->getReferencedConstructClass());
        $this->setAttribute(XMLReference::ATTRIBUTE_SP_FINDER_NAME, $referenceGeneratorSource->getStoredProcedureFinderName());
        $this->setAttribute(XMLReference::ATTRIBUTE_SP_DELETER_NAME, $referenceGeneratorSource->getStoredProcedureDeleterName());
        $this->setAttribute(XMLReference::ATTRIBUTE_FOREIGN_METHOD_NAME, $referenceGeneratorSource->getForeignMethodName());
        $this->setAttributeAsBool(XMLReference::ATTRIBUTE_SP_REFERENCE_CREATOR_NEEDED, $referenceGeneratorSource->isReferenceCreatorNeeded());

        $this->addForeignEntityManagerData($referenceGeneratorSource);
        $this->addForeignConstructData($referenceGeneratorSource);

        // attach referenced columns to xml
        $referencedColumnList = $referenceGeneratorSource->getReferencedColumnList();
        foreach ($referencedColumnList as $referencedColumn) {
            $this->addReferencedColumn($this->domElement, $referencedColumn);
        }

        // attach collector filter
        foreach ($referenceGeneratorSource->getCollectorFilterSourceList() as $filter) {
            $this->addCollectorFilter($filter);
        }
    }

    /**
     * @param ReferenceGeneratorSource $referenceGeneratorSource
     */
    protected function addForeignEntityManagerData(ReferenceGeneratorSource $referenceGeneratorSource)
    {
        $referencedEntity = $referenceGeneratorSource->getReferencedEntity();
        new XMLEntityManagerBuilder($referencedEntity->getEntityManagerSource(), $this->domDocument, $this->domElement);
    }

    /**
     * @param ReferenceGeneratorSource $referenceGeneratorSource
     */
    protected function addForeignConstructData(ReferenceGeneratorSource $referenceGeneratorSource)
    {
        $referencedEntity = $referenceGeneratorSource->getReferencedEntity();
        new XMLConstructBuilder($referencedEntity->getConstructSource(), $this->domDocument, $this->domElement);
    }

    /**
     * a referenced column refers to a primary key attribute in the referenced entity
     *
     * @param \DOMElement $xmlReferencedColumnList
     * @param ReferencedColumnSource $referencedColumn
     *
     * @return void
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
        $xmlReferencedColumn->setAttribute(XMLReference::ATTRIBUTE_COLUMN_REFERENCED_METHOD_NAME, $referencedColumn->getReferencedColumnMethodName());

        $xmlReferencedColumn->setAttribute(XMLAttribute::ATTRIBUTE_CONST_DB_NAME, strtoupper($referencedColumn->getDatabaseName()));

    }

    /**
     * @param CollectorFilterSource $filterSource
     */
    protected function addCollectorFilter(CollectorFilterSource $filterSource)
    {
        $xmlCollectorFilter = $this->createElement($this->domElement, XMLCollectorFilter::ELEMENT_FILTER_NAME);
        $xmlCollectorFilter->setAttribute(XMLCollectorFilter::ATTRIBUTE_FILTER_NAME, $filterSource->getName());
        $xmlCollectorFilter->setAttribute(XMLCollectorFilter::ATTRIBUTE_FILTER_FILTER, $filterSource->getFilter());
        $xmlCollectorFilter->setAttribute(XMLCollectorFilter::ATTRIBUTE_SP_NAME, $filterSource->getSPName());

        foreach ($filterSource->getSPParameterList() as $parameter) {
            $this->addParameter($xmlCollectorFilter, $parameter);
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