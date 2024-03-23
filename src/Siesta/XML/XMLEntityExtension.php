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
     * @var bool
     */
    protected bool $hasChangedSinceLastGeneration;

    /**
     * @var string|null
     */
    protected ?string $tableName;

    /**
     * @var XMLConstructor|null
     */
    protected ?XMLConstructor $xmlConstructor;

    /**
     * @var XMLServiceClass|null
     */
    protected ?XMLServiceClass $xmlServiceClass;

    /**
     * @var XMLAttribute[]
     */
    protected array $xmlAttributeList;

    /**
     * @var XMLReference[]
     */
    protected array $xmlReferenceList;

    /**
     * @var XMLIndex[]
     */
    protected array $xmlIndexList;

    /**
     * @var XMLCollection[]
     */
    protected array $xmlCollectionList;

    /**
     * @var XMLCollectionMany[]
     */
    protected array $xmlCollectionManyList;

    /**
     * @var XMLDynamicCollection[]
     */
    protected array $xmlDynamicCollectionList;

    /**
     * @var XMLStoredProcedure[]
     */
    protected array $xmlStoredProcedureList;

    /**
     * @var XMLValueObject[]
     */
    protected array $xmlValueObjectList;

    /**
     * @var XMLAccess|null
     */
    protected ?XMLAccess $xmlAccess;


    /**
     * XMLEntity constructor.
     */
    public function __construct()
    {
        $this->hasChangedSinceLastGeneration = true;
        $this->tableName = null;
        $this->xmlConstructor = null;
        $this->xmlServiceClass = null;
        $this->xmlAttributeList = [];
        $this->xmlReferenceList = [];
        $this->xmlIndexList = [];
        $this->xmlCollectionList = [];
        $this->xmlCollectionManyList = [];
        $this->xmlDynamicCollectionList = [];
        $this->xmlStoredProcedureList = [];
        $this->xmlValueObjectList = [];
        $this->xmlAccess = null;

    }


    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
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
    protected function readXMLConstructor(XMLAccess $xmlAccess): void
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
    protected function readXMLServiceClass(XMLAccess $xmlAccess): void
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
    protected function readEntityDataFromXML(XMLAccess $xmlAccess): void
    {
        $this->setTableName($xmlAccess->getAttribute(self::TABLE_NAME));
    }


    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readAttributeDataFromXML(XMLAccess $xmlAccess): void
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
    protected function readReferenceDataFromXML(XMLAccess $xmlAccess): void
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
    protected function readIndexDataFromXML(XMLAccess $xmlAccess): void
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
    protected function readCollectionFromXML(XMLAccess $xmlAccess): void
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
    protected function readDynamicCollection(XMLAccess $xmlAccess): void
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
    protected function readCollectionManyFromXML(XMLAccess $xmlAccess): void
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
    protected function readStoredProcedureDataFromXML(XMLAccess $xmlAccess): void
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
    protected function readValueObjectFromXML(XMLAccess $xmlAccess): void
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
    public function getXMLChildElementListByName(string $childName): array
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
    public function getCustomAttribute(string $name): ?string
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
     * @return string|null
     */
    public function getTableName(): ?string
    {
        return $this->tableName;
    }


    /**
     * @param string|null $tableName
     */
    public function setTableName(string $tableName = null): void
    {
        $this->tableName = $tableName;
    }


    /**
     * @return XMLAccess|null
     */
    public function getXmlAccess(): ?XMLAccess
    {
        return $this->xmlAccess;
    }


    /**
     * @param XMLAccess|null $xmlAccess
     */
    public function setXmlAccess(?XMLAccess $xmlAccess): void
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


    /**
     * @return bool|null
     */
    public function hasChangedSinceLastGeneration(): ?bool
    {
        return $this->hasChangedSinceLastGeneration;
    }


    /**
     * @param bool $hasChangedSinceLastGeneration
     */
    public function setHasChangedSinceLastGeneration(bool $hasChangedSinceLastGeneration): void
    {
        $this->hasChangedSinceLastGeneration = $hasChangedSinceLastGeneration;
    }


}