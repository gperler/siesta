<?php
declare(strict_types=1);

namespace Siesta\XML;

/**
 * @author Gregor Müller
 */
class XMLEntityExtension
{

    const ELEMENT_ENTITY_NAME = "entity-extension";

    const TABLE_NAME = "table";

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var XMLConstructor
     */
    protected $xmlConstructor;

    /**
     * @var XMLServiceClass
     */
    protected $xmlServiceClass;

    /**
     * @var XMLAttribute[]
     */
    protected $xmlAttributeList;

    /**
     * @var XMLReference[]
     */
    protected $xmlReferenceList;

    /**
     * @var XMLIndex[]
     */
    protected $xmlIndexList;

    /**
     * @var XMLCollection[]
     */
    protected $xmlCollectionList;

    /**
     * @var XMLCollectionMany[]
     */
    protected $xmlCollectionManyList;

    /**
     * @var XMLDynamicCollection[]
     */
    protected $xmlDynamicCollectionList;

    /**
     * @var XMLStoredProcedure[]
     */
    protected $xmlStoredProcedureList;

    /**
     * @var XMLValueObject[]
     */
    protected $xmlValueObjectList;

    /**
     * @var XMLAccess
     */
    protected $xmlAccess;

    /**
     * XMLEntity constructor.
     */
    public function __construct()
    {
        $this->xmlAttributeList = [];
        $this->xmlReferenceList = [];
        $this->xmlIndexList = [];
        $this->xmlCollectionList = [];
        $this->xmlCollectionManyList = [];
        $this->xmlDynamicCollectionList = [];
        $this->xmlStoredProcedureList = [];
        $this->xmlValueObjectList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->xmlAccess = $xmlAccess;
        $this->readXMLConstructor($xmlAccess);
        $this->readXMLServiceClass($xmlAccess);
        $this->readEntityDataFromXML($xmlAccess);
        $this->readAttributeDataFromXML($xmlAccess);
        $this->readReferenceDataFromXML($xmlAccess);
        $this->readIndexDataFromXML($xmlAccess);
        $this->readCollectionFromXML($xmlAccess);
        $this->readCollectionManyFromXML($xmlAccess);
        $this->readDynamicCollection($xmlAccess);
        $this->readStoredProcedureDataFromXML($xmlAccess);
        $this->readValueObjectFromXML($xmlAccess);
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readXMLConstructor(XMLAccess $xmlAccess)
    {
        $xmlConstructorAccess = $xmlAccess->getFirstChildByName(XMLConstructor::ELEMENT_CONSTRUCTOR_NAME);
        if ($xmlConstructorAccess === null) {
            return;
        }
        $this->xmlConstructor = new XMLConstructor();
        $this->xmlConstructor->fromXML($xmlConstructorAccess);
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readXMLServiceClass(XMLAccess $xmlAccess)
    {
        $xmlServiceClass = $xmlAccess->getFirstChildByName(XMLServiceClass::ELEMENT_SERVICE_CLASS_NAME);
        if ($xmlServiceClass === null) {
            return;
        }
        $this->xmlServiceClass = new XMLServiceClass();
        $this->xmlServiceClass->fromXMLAccess($xmlServiceClass);

    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readEntityDataFromXML(XMLAccess $xmlAccess)
    {
        $this->setTableName($xmlAccess->getAttribute(self::TABLE_NAME));
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readAttributeDataFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLAttribute::ELEMENT_ATTRIBUTE_NAME) as $xmlAttributeAccess) {
            $xmlAttribute = new XMLAttribute();
            $xmlAttribute->fromXML($xmlAttributeAccess);
            $this->xmlAttributeList[] = $xmlAttribute;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readReferenceDataFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLReference::ELEMENT_REFERENCE_NAME) as $xmlReferenceAccess) {
            $xmlReference = new XMLReference();
            $xmlReference->fromXML($xmlReferenceAccess);
            $this->xmlReferenceList[] = $xmlReference;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readIndexDataFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLIndex::ELEMENT_INDEX_NAME) as $xmlIndexAccess) {
            $xmlIndex = new XMLIndex();
            $xmlIndex->fromXML($xmlIndexAccess);
            $this->xmlIndexList[] = $xmlIndex;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readCollectionFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLCollection::ELEMENT_COLLECTION_NAME) as $xmlCollectionAccess) {
            $xmlCollection = new XMLCollection();
            $xmlCollection->fromXML($xmlCollectionAccess);
            $this->xmlCollectionList[] = $xmlCollection;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readDynamicCollection(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLDynamicCollection::ELEMENT_DYNAMIC_COLLECTION_NAME) as $xmlDynamicCollectionAccess) {
            $xmlDynamicCollection = new XMLDynamicCollection();
            $xmlDynamicCollection->fromXML($xmlDynamicCollectionAccess);
            $this->xmlDynamicCollectionList[] = $xmlDynamicCollection;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readCollectionManyFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLCollectionMany::ELEMENT_COLLECTION_MANY_NAME) as $xmlCollectionManyAccess) {
            $xmlCollectionMany = new XMLCollectionMany();
            $xmlCollectionMany->fromXML($xmlCollectionManyAccess);
            $this->xmlCollectionManyList[] = $xmlCollectionMany;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readStoredProcedureDataFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLStoredProcedure::ELEMENT_SP_NAME) as $xmlSPAccess) {
            $xmlSP = new XMLStoredProcedure();
            $xmlSP->fromXML($xmlSPAccess);
            $this->xmlStoredProcedureList[] = $xmlSP;
        }
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readValueObjectFromXML(XMLAccess $xmlAccess)
    {
        foreach ($xmlAccess->getXMLChildElementListByName(XMLValueObject::ELEMENT_VALUE_OBJECT) as $xmlValueObjectAccess) {
            $xmlValueObject = new XMLValueObject();
            $xmlValueObject->fromXML($xmlValueObjectAccess);
            $this->xmlValueObjectList[] = $xmlValueObject;
        }
    }

    /**
     * @return XMLAttribute[]
     */
    public function getXMLAttributeList(): array
    {
        return $this->xmlAttributeList;
    }

    /**
     * @param string $childName
     *
     * @return XMLAccess[]
     */
    public function getXMLChildElementListByName(string $childName)
    {
        return $this->xmlAccess->getXMLChildElementListByName($childName);
    }

    /**
     * @return XMLReference[]
     */
    public function getXMLReferenceList(): array
    {
        return $this->xmlReferenceList;
    }

    /**
     * @return XMLIndex[]
     */
    public function getXMLIndexList(): array
    {
        return $this->xmlIndexList;
    }

    /**
     * @return XMLCollection[]
     */
    public function getXMLCollectionList(): array
    {
        return $this->xmlCollectionList;
    }

    /**
     * @return XMLCollectionMany[]
     */
    public function getXMLCollectionManyList(): array
    {
        return $this->xmlCollectionManyList;
    }

    /**
     * @return XMLDynamicCollection[]
     */
    public function getXMLDynamicCollectionList(): array
    {
        return $this->xmlDynamicCollectionList;
    }

    /**
     * @return XMLStoredProcedure[]
     */
    public function getXMLStoredProcedureList(): array
    {
        return $this->xmlStoredProcedureList;
    }

    /**
     * @return XMLValueObject[]
     */
    public function getXMLValueObjectList(): array
    {
        return $this->xmlValueObjectList;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getCustomAttribute(string $name)
    {
        if ($this->xmlAccess === null) {
            return null;
        }
        return $this->xmlAccess->getAttribute($name);
    }

    /**
     * @return string[]
     */
    public function getCustomAttributeList(): array
    {
        return $this->xmlAccess->getAttributeList();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName = null)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return XMLAccess
     */
    public function getXmlAccess()
    {
        return $this->xmlAccess;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function setXmlAccess($xmlAccess)
    {
        $this->xmlAccess = $xmlAccess;
    }

    /**
     * @return XMLConstructor
     */
    public function getXmlConstructor(): XMLConstructor
    {
        return $this->xmlConstructor;
    }

    /**
     * @return XMLServiceClass
     */
    public function getXmlServiceClass(): XMLServiceClass
    {
        return $this->xmlServiceClass;
    }


}