<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 28.06.15
 * Time: 21:53
 */

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\entity\EntityTransformerSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\naming\StoredProcedureNaming;
use siestaphp\naming\XMLAttribute;
use siestaphp\naming\XMLCollector;
use siestaphp\naming\XMLEntity;
use siestaphp\naming\XMLReference;
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
     */
    private function buildXML()
    {
        $this->addEntityData();
        $this->addAttributeData();
        $this->addReferenceData();
        $this->addCollectorData();

        // if this is used for transformation (and not reverse engineering) add transformation relevant data
        if ($this->entitySource instanceof EntityTransformerSource) {
            $this->addTransformerData($this->entitySource);
        }

    }

    /**
     * add the data on entity level
     */
    private function addEntityData()
    {
        $this->setAttribute(XMLEntity::ATTRIBUTE_CLASS_NAME, $this->entitySource->getClassName());
        $this->setAttribute(XMLEntity::ATTRIBUTE_CLASS_NAMESPACE, $this->entitySource->getClassNamespace());
        $this->setAttribute(XMLEntity::ATTRIBUTE_CONSTRUCTOR_CLASS, $this->entitySource->getConstructorClass());
        $this->setAttribute(XMLEntity::ATTRIBUTE_CONSTRUCTOR_NAMESPACE, $this->entitySource->getConstructorNamespace());
        $this->setAttribute(XMLEntity::ATTRIBUTE_TARGET_PATH, $this->entitySource->getTargetPath());
        $this->setAttribute(XMLEntity::ATTRIBUTE_TABLE, $this->entitySource->getTable());
        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_DELIMIT, $this->entitySource->isDelimit());

    }

    /**
     * adds the attribute data to the xml
     */
    private function addAttributeData()
    {
        // create element
        $attributeXMLList = $this->createElement($this->domElement, XMLAttribute::ELEMENT_ATTRIBUTE_LIST_NAME);

        // iterate attributes and create XML Builder for them
        foreach ($this->entitySource->getAttributeSourceList() as $attributeSource) {
            $this->attributeList[] = new XMLAttributeBuilder($attributeSource, $this->domDocument, $attributeXMLList);
        }
    }

    /**
     * adds the reference data to the xml
     */
    private function addReferenceData()
    {
        // create element
        $referenceXMLList = $this->createElement($this->domElement, XMLReference::ELEMENT_REFERENCE_LIST_NAME);

        // iterate references and create XML builder for them
        foreach ($this->entitySource->getReferenceSourceList() as $referenceSource) {
            $this->referenceList[] = new XMLReferenceBuilder($referenceSource, $this->domDocument, $referenceXMLList);
        }
    }

    /**
     * add collector data to the xml
     */
    private function addCollectorData()
    {
        // create element
        $collectorXMLList = $this->createElement($this->domElement, XMLCollector::ELEMENT_COLLECTOR_LIST_NAME);

        // iterate collectors and create XML builder for them
        foreach ($this->entitySource->getCollectorSourceList() as $collectorSource) {
            $this->collectorList[] = new XMLCollectorBuilder($collectorSource, $this->domDocument, $collectorXMLList);
        }
    }

    /**
     * adds data that is used for transformation
     *
     * @param EntityTransformerSource $ets
     */
    private function addTransformerData(EntityTransformerSource $ets)
    {
        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_DATETIME_IN_USE, $ets->isDateTimeUsed());

        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_HAS_REFERENCES, $ets->hasReferences());

        $this->setAttributeAsBool(XMLEntity::ATTRIBUTE_HAS_ATTRIBUTES, $ets->hasAttributes());

        $this->addStoredProcedureNames();

        $this->addReferencedClassUseNames($ets);

        $this->addFindByPrimaryKeySignature($ets);

        $this->addCustomStoredProcedureList($ets->getStoredProcedureSourceList());

    }

    /**
     * adds stored procedure names for default stored procedures to the XML
     */
    private function addStoredProcedureNames()
    {
        $xmlSP = $this->createElement($this->domElement, XMLEntity::ELEMENT_STANDARD_STORED_PROCEDURES);

        $tableName = $this->entitySource->getTable();

        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_FIND_BY_PK, StoredProcedureNaming::getSPFindByPrimaryKeyName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_DELETE_BY_PK, StoredProcedureNaming::getSPDeleteByPrimaryKeyName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_INSERT, StoredProcedureNaming::getSPInsertName($tableName));
        $xmlSP->setAttribute(XMLEntity::ATTRIBUTE_SSP_UPDATE, StoredProcedureNaming::getSPUpdateName($tableName));

    }

    /**
     * adds all classes that a use statement is needed for
     *
     * @param EntityTransformerSource $ets
     */
    private function addReferencedClassUseNames(EntityTransformerSource $ets)
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
     * adds the signature for find by primary key
     *
     * @param EntityTransformerSource $ets
     */
    private function addFindByPrimaryKeySignature(EntityTransformerSource $ets)
    {
        $this->setAttribute(XMLEntity::ATTRIBUTE_FIND_BY_PK_SIGNATURE, $ets->getFindByPKSignature());
        $this->setAttribute(XMLEntity::ATTRIBUTE_STORED_PROCEDURE_CALL_SIGNATURE, $ets->getSPCallSignature());
    }

    /**
     * adds information for custom stored procedures
     *
     * @param StoredProcedureSource[] $spSourceList
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

}