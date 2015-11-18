<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\construct\ConstructSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\manager\EntityManagerSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\naming\XMLAttribute;
use siestaphp\naming\XMLCollector;
use siestaphp\naming\XMLConstruct;
use siestaphp\naming\XMLEntity;
use siestaphp\naming\XMLEntityManager;
use siestaphp\naming\XMLIndex;
use siestaphp\naming\XMLReference;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class XMLEntity
 * @package siestaphp\datamodel\xml
 */
class XMLEntityReader extends XMLAccess implements EntitySource
{

    /**
     * @var AttributeSource[]
     */
    protected $attributeReaderList;

    /**
     * @var ReferenceSource[]
     */
    protected $referenceReaderList;

    /**
     * @var CollectorSource[]
     */
    protected $collectorSourceList;

    /**
     * @var StoredProcedureSource[]
     */
    protected $storedProcedureList;

    /**
     * @var IndexSource[]
     */
    protected $indexList;

    /**
     * @var EntityManagerSource
     */
    protected $entityManagerSource;

    /**
     * @var ConstructSource
     */
    protected $constructSource;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return AttributeSource[]
     */
    public function getAttributeSourceList()
    {
        if ($this->attributeReaderList === null) {
            $this->readAttributeList();
        }
        return $this->attributeReaderList;
    }

    /**
     *  parses the attribute list
     * @return void
     */
    private function readAttributeList()
    {
        $this->attributeReaderList = [];
        $attributeXMLList = $this->getXMLChildElementListByName(XMLAttribute::ELEMENT_ATTRIBUTE_NAME);

        foreach ($attributeXMLList as $attributeXML) {
            $attributeReader = new XMLAttributeReader();
            $attributeReader->setSource($attributeXML);
            $this->attributeReaderList[] = $attributeReader;
        }
    }

    /**
     * @return ReferenceSource[]
     */
    public function getReferenceSourceList()
    {
        if ($this->referenceReaderList === null) {
            $this->readReferenceList();

        }
        return $this->referenceReaderList;
    }

    /**
     * @return void
     */
    private function readReferenceList()
    {
        $this->referenceReaderList = [];
        $referenceXMLList = $this->getXMLChildElementListByName(XMLReference::ELEMENT_REFERENCE_NAME);
        foreach ($referenceXMLList as $referenceXML) {
            $referenceReader = new XMLReferenceReader();
            $referenceReader->setSource($referenceXML);
            $this->referenceReaderList[] = $referenceReader;
        }

    }

    /**
     * @return CollectorSource[]
     */
    public function getCollectorSourceList()
    {
        if ($this->collectorSourceList === null) {
            $this->readCollectorList();
        }

        return $this->collectorSourceList;
    }

    /**
     * @return void
     */
    private function readCollectorList()
    {
        $this->collectorSourceList = [];
        $collectorXMLList = $this->getXMLChildElementListByName(XMLCollector::ELEMENT_COLLECTOR_NAME);
        foreach ($collectorXMLList as $collectorXML) {
            $collectorReader = new XMLCollectorReader();
            $collectorReader->setSource($collectorXML);
            $this->collectorSourceList[] = $collectorReader;
        }
    }

    /**
     * @return StoredProcedureSource[]
     */
    public function getStoredProcedureSourceList()
    {
        if ($this->storedProcedureList === null) {
            $this->readStoredProcedureList();
        }
        return $this->storedProcedureList;
    }

    /**
     * @return void
     */
    private function readStoredProcedureList()
    {
        $this->storedProcedureList = [];
        $storedProcedureXMLList = $this->getXMLChildElementListByName(XMLStoredProcedure::ELEMENT_STORED_PROCEDURE);
        foreach ($storedProcedureXMLList as $storedProcedureXML) {
            $spReader = new XMLStoredProcedureReader();
            $spReader->setSource($storedProcedureXML);
            $this->storedProcedureList[] = $spReader;
        }
    }

    /**
     * @return IndexSource[]
     */
    public function getIndexSourceList()
    {
        if ($this->indexList === null) {
            $this->readIndexSourceList();
        }

        return $this->indexList;
    }

    /**
     * @return void
     */
    private function readIndexSourceList()
    {
        $this->indexList = [];
        $indexXMLList = $this->getXMLChildElementListByName(XMLIndex::ELEMENT_INDEX_NAME);
        foreach ($indexXMLList as $indexXML) {
            $indexReader = new XMLIndexReader();
            $indexReader->setSource($indexXML);
            $this->indexList[] = $indexReader;
        }
    }

    /**
     * @return EntityManagerSource
     */
    public function getEntityManagerSource()
    {
        if ($this->entityManagerSource === null) {
            $this->readEntityManager();
        }
        return $this->entityManagerSource;
    }

    /**
     * @return void
     */
    private function readEntityManager()
    {
        $managerXMLList = $this->getXMLChildElementListByName(XMLEntityManager::ELEMENT_NAME);
        foreach ($managerXMLList as $managerXML) {
            $this->entityManagerSource = new XMLEntityManagerReader();
            $this->entityManagerSource->setSource($managerXML);
            return;
        }
    }

    /**
     * @return ConstructSource
     */
    public function getConstructSource()
    {
        if ($this->constructSource === null) {
            $this->readConstructSource();
        }
        return $this->constructSource;
    }

    private function readConstructSource()
    {
        $constructXMLList = $this->getXMLChildElementListByName(XMLConstruct::ELEMENT_CONSTRUCT_NAME);
        foreach ($constructXMLList as $constructXML) {
            $this->constructSource = new XMLConstructReader();
            $this->constructSource->setSource($constructXML);
            return;
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getAttribute(XMLEntity::ATTRIBUTE_CLASS_NAME);
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
        return $this->getAttribute(XMLEntity::ATTRIBUTE_CLASS_NAMESPACE);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->getAttribute(XMLEntity::ATTRIBUTE_TABLE);
    }

    /**
     * @return boolean
     */
    public function isDelimit()
    {
        return $this->getAttributeAsBool(XMLEntity::ATTRIBUTE_DELIMIT);
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->getAttribute(XMLEntity::ATTRIBUTE_TARGET_PATH);
    }

}