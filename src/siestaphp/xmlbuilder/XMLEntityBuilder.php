<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\naming\StoredProcedureNaming;
use siestaphp\naming\XMLAttribute;
use siestaphp\naming\XMLEntity;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class XMLEntityBuilder builds the Entity XML for transformation or reverse engineering
 * @package siestaphp\xmlbuilder
 */
class XMLEntityBuilder extends XMLBuilder
{

    /**
     * @var EntitySource
     */
    protected $entitySource;

    /**
     * @var XMLAttributeBuilder[]
     */
    protected $attributeList;

    /**
     * @var XMLReferenceBuilder[]
     */
    protected $referenceList;

    /**
     * @var XMLCollectorBuilder[]
     */
    protected $collectorList;

    /**
     * @param EntitySource $entitySource
     */
    public function __construct(EntitySource $entitySource)
    {
        parent::__construct(null);

        $this->entitySource = $entitySource;

        $this->domElement = $this->createElement($this->domDocument, XMLEntity::ELEMENT_ENTITY_NAME);

        $this->attributeList = array();
        $this->referenceList = array();
        $this->collectorList = array();

        $this->buildXML();

    }

    /**
     * builds the XML document for the entity
     * @return void
     */
    private function buildXML()
    {
        $this->addEntityData();
        $this->addAttributeData();
        $this->addReferenceData();
        $this->addCollectorData();

        $this->addIndexList();

        // if this is used for transformation (and not reverse engineering) add transformation relevant data
        if ($this->entitySource instanceof EntityGeneratorSource) {
            $this->addTransformerData($this->entitySource);
        }

    }

    /**
     * add the data on entity level
     * @return void
     */
    private function addEntityData()
    {
        $managerXMLBuilder = new XMLEntityManagerBuilder($this->entitySource->getEntityManagerSource(), $this->domDocument, $this->domElement);
        $constructXMLBuilder = new XMLConstructBuilder($this->entitySource->getConstructSource(), $this->domDocument, $this->domElement);

        $this->setAttribute(XMLEntity::ATTRIBUTE_CLASS_NAME, $this->entitySource->getClassName());
        $this->setAttribute(XMLEntity::ATTRIBUTE_CLASS_NAMESPACE, $this->entitySource->getClassNamespace());

        $this->setAttribute(XMLEntity::ATTRIBUTE_TARGET_PATH, $this->entitySource->getTargetPath());
        $this->setAttribute(XMLEntity::ATTRIBUTE_TABLE, $this->entitySource->getTable());
        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_DELIMIT, $this->entitySource->isDelimit());

    }

    /**
     * adds the attribute data to the xml
     * @return void
     */
    private function addAttributeData()
    {
        // iterate attributes and create XML Builder for them
        foreach ($this->entitySource->getAttributeSourceList() as $attributeSource) {
            $this->attributeList[] = new XMLAttributeBuilder($attributeSource, $this->domDocument, $this->domElement);
        }
    }

    /**
     * adds the reference data to the xml
     * @return void
     */
    private function addReferenceData()
    {
        // iterate references and create XML builder for them
        foreach ($this->entitySource->getReferenceSourceList() as $referenceSource) {
            $this->referenceList[] = new XMLReferenceBuilder($referenceSource, $this->domDocument, $this->domElement);
        }
    }

    /**
     * add collector data to the xml
     * @return void
     */
    private function addCollectorData()
    {
        // iterate collectors and create XML builder for them
        foreach ($this->entitySource->getCollectorSourceList() as $collectorSource) {
            $this->collectorList[] = new XMLCollectorBuilder($collectorSource, $this->domDocument, $this->domElement);
        }
    }

    /**
     * adds the index list to the xml
     * @return void
     */
    private function addIndexList()
    {
        foreach ($this->entitySource->getIndexSourceList() as $indexSource) {
            $indexXMLBuilder = new XMLIndexBuilder($indexSource, $this->domDocument, $this->domElement);
        }

    }

    /**
     * adds data that is used for transformation
     *
     * @param EntityGeneratorSource $source
     *
     * @return void
     */
    private function addTransformerData(EntityGeneratorSource $source)
    {

        $this->setAttribute(XMLEntity::ATTRIBUTE_DELIMIT_TABLE_NAME, $source->getDelimitTable());

        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_DATETIME_IN_USE, $source->isDateTimeUsed());
        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_HAS_REFERENCES, $source->hasReferences());
        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_HAS_ATTRIBUTES, $source->hasAttributes());
        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_HAS_PRIMARY_KEY, $source->hasPrimaryKey());

        $this->addStoredProcedureNames();
        $this->addReferencedClassUseNames($source);
        $this->addCustomStoredProcedureList($source->getStoredProcedureSourceList());
        $this->addPrimaryKeyColumns($source);
        $this->addNMMappingData($source);

    }

    /**
     * @param EntityGeneratorSource $source
     */
    protected function addNMMappingData(EntityGeneratorSource $source)
    {
        foreach ($source->getNMMappingSourceList() as $nmMapping) {
            $xmlNMMapping = new XMLNMMappingBuilder($nmMapping, $this->domDocument, $this->domElement);
        }
    }

    /**
     * adds stored procedure names for default stored procedures to the XML
     * @return void
     */
    private function addStoredProcedureNames()
    {
        $xmlSP = $this->createElement($this->domElement, XMLEntity::ELEMENT_STANDARD_STORED_PROCEDURES);

        $tableName = $this->entitySource->getTable();

        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_FIND_BY_PK, StoredProcedureNaming::getSPFindByPrimaryKeyName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_DELETE_BY_PK, StoredProcedureNaming::getSPDeleteByPrimaryKeyName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_INSERT, StoredProcedureNaming::getSPInsertName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_UPDATE, StoredProcedureNaming::getSPUpdateName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_FIND_BY_PK_DELIMIT, StoredProcedureNaming::getSPFindByPrimaryKeyDelimitName($tableName));
    }

    /**
     * adds all classes that a use statement is needed for
     *
     * @param EntityGeneratorSource $ets
     *
     * @return void
     */
    private function addReferencedClassUseNames(EntityGeneratorSource $ets)
    {
        // create XML Element to contain used classes
        $xmlUseList = $this->createElement($this->domElement, XMLEntity::ELEMENT_REFERENCE_USE_FQ_CLASS_NAME_LIST);

        // get used classes from entity
        $useList = $ets->getUsedFQClassNames();

        // add used classed to xml
        foreach ($useList as $use) {
            $xmlUse = $this->createElement($xmlUseList, XMLEntity::ELEMENT_REFERENCE_USE_FQ_CLASS_NAME);
            $xmlUse->setAttribute(XMLEntity::ATTRIBUTE_REFERENCE_USE_NAME, $use);
        }
    }

    /**
     * adds information for custom stored procedures
     *
     * @param StoredProcedureSource[] $spSourceList
     *
     * @return void
     */
    private function addCustomStoredProcedureList($spSourceList)
    {
        // create xml element as container
        $xmlSPList = $this->createElement($this->domElement, XMLStoredProcedure::ELEMENT_STORED_PROCEDURE_LIST);

        // iterate stored procedures and add them to XML
        foreach ($spSourceList as $spSource) {
            $spXMLBuilder = new XMLSPBuilder($spSource, $this->domDocument, $xmlSPList);
        }

    }

    /**
     * @param EntityGeneratorSource $ets
     *
     * @return void
     */
    private function addPrimaryKeyColumns(EntityGeneratorSource $ets)
    {

        foreach ($ets->getPrimaryKeyColumns() as $column) {
            $xmlPKColumn = $this->createElement($this->domElement, XMLAttribute::ELEMENT_PK_COLUMN_NAME);

            $xmlPKColumn->setAttribute(XMLAttribute::ATTRIBUTE_NAME, $column->getName());
            $xmlPKColumn->setAttribute(XMLAttribute::ATTRIBUTE_TYPE, $column->getPHPType());
        }
    }

}