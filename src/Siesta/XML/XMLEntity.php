<?php

declare(strict_types=1);

namespace Siesta\XML;

use Siesta\Database\MetaData\TableMetaData;
use Siesta\NamingStrategy\NamingStrategyRegistry;

/**
 * @author Gregor Müller
 */
class XMLEntity
{

    const ELEMENT_ENTITY_NAME = "entity";

    const CLASS_SHORT_NAME = "name";

    const NAMESPACE_NAME = "namespace";

    const TABLE_NAME = "table";

    const DELIMIT = "delimit";

    const TARGET_PATH = "targetPath";

    const REPLICATION = "replication";


    /**
     * @var bool
     */
    protected bool $hasChangedSinceLastGeneration;

    /**
     * @var string|null
     */
    protected ?string $classShortName;

    /**
     * @var string|null
     */
    protected ?string $namespaceName;

    /**
     * @var string|null
     */
    protected ?string $tableName;

    /**
     * @var bool
     */
    protected bool $isDelimit;

    /**
     * @var bool;
     */
    protected bool $isReplication;

    /**
     * @var string|null
     */
    protected ?string $targetPath;

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
     * @var array
     */
    protected array $databaseSpecific;


    /**
     * XMLEntity constructor.
     */
    public function __construct()
    {
        $this->hasChangedSinceLastGeneration = true;
        $this->classShortName = null;
        $this->namespaceName = null;
        $this->tableName = null;
        $this->isDelimit = false;
        $this->isReplication = false;
        $this->targetPath = null;
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
        $this->databaseSpecific = [];
        $this->xmlAccess = null;
    }


    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent): void
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_ENTITY_NAME);
        $xmlWrite->setAttribute(self::CLASS_SHORT_NAME, $this->getClassShortName());
        $xmlWrite->setAttribute(self::NAMESPACE_NAME, $this->getNamespaceName());
        $xmlWrite->setAttribute(self::TABLE_NAME, $this->getTableName());
        $xmlWrite->setAttribute(self::TARGET_PATH, $this->getTargetPath());
        $this->databaseSpecificToXML($xmlWrite);
        foreach ($this->getXMLAttributeList() as $xmlAttribute) {
            $xmlAttribute->toXML($xmlWrite);
        }
        foreach ($this->getXMLReferenceList() as $xmlReference) {
            $xmlReference->toXML($xmlWrite);
        }
        foreach ($this->getXMLIndexList() as $xmlIndex) {
            $xmlIndex->toXML($xmlWrite);
        }
    }


    /**
     * @param XMLWrite $parent
     */
    protected function databaseSpecificToXML(XMLWrite $parent): void
    {
        foreach ($this->databaseSpecific as $dbName => $keyValueList) {
            $dbSpecific = $parent->appendChild(self::ELEMENT_ENTITY_NAME . '-' . $dbName);
            foreach ($keyValueList as $key => $value) {
                $dbSpecific->setAttribute($key, $value);
            }
        }
    }


    /**
     * @param TableMetaData $table
     */
    public function fromTable(TableMetaData $table): void
    {
        $tableName = $table->getName();
        $classNaming = NamingStrategyRegistry::getClassNamingStrategy();
        $className = $classNaming->transform($tableName);

        $this->setTableName($tableName);
        $this->setClassShortName($className);
        $this->databaseSpecific = $table->getDataBaseSpecific();

        foreach ($table->getColumnList() as $column) {
            $xmlAttribute = new XMLAttribute();
            $xmlAttribute->fromColumnMetaData($column);
            $this->xmlAttributeList[] = $xmlAttribute;
        }

        foreach ($table->getConstraintList() as $constraint) {
            $xmlReference = new XMLReference();
            $xmlReference->fromConstraintMetaData($constraint);
            $this->xmlReferenceList[] = $xmlReference;
        }

        foreach ($table->getIndexList() as $indexMetaData) {
            $xmlIndex = new XMLIndex();
            $xmlIndex->fromIndexMetaData($indexMetaData);
            $this->xmlIndexList[] = $xmlIndex;
        }
    }


    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->xmlAccess = $xmlAccess;
        $this->readEntityDataFromXML($xmlAccess);
        $this->readXMLConstructor($xmlAccess);
        $this->readXMLServiceClass($xmlAccess);
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
     * @param XMLEntityExtension $xmlEntityExtension
     */
    public function addExtension(XMLEntityExtension $xmlEntityExtension): void
    {
        $this->xmlAttributeList = array_merge($this->xmlAttributeList, $xmlEntityExtension->getXMLAttributeList());
        $this->xmlReferenceList = array_merge($this->xmlReferenceList, $xmlEntityExtension->getXMLReferenceList());
        $this->xmlIndexList = array_merge($this->xmlIndexList, $xmlEntityExtension->getXMLIndexList());
        $this->xmlCollectionList = array_merge($this->xmlCollectionList, $xmlEntityExtension->getXMLCollectionList());
        $this->xmlCollectionManyList = array_merge($this->xmlCollectionManyList, $xmlEntityExtension->getXMLCollectionManyList());
        $this->xmlDynamicCollectionList = array_merge($this->xmlDynamicCollectionList, $xmlEntityExtension->getXMLDynamicCollectionList());
        $this->xmlStoredProcedureList = array_merge($this->xmlStoredProcedureList, $xmlEntityExtension->getXMLStoredProcedureList());
        $this->xmlValueObjectList = array_merge($this->xmlValueObjectList, $xmlEntityExtension->getXMLValueObjectList());
        $this->xmlConstructor = $xmlEntityExtension->getXmlConstructor();
        $this->xmlServiceClass = $xmlEntityExtension->getXmlServiceClass();
        $this->hasChangedSinceLastGeneration = $this->hasChangedSinceLastGeneration || $xmlEntityExtension->hasChangedSinceLastGeneration();
    }


    /**
     * @param XMLAccess $xmlAccess
     */
    protected function readEntityDataFromXML(XMLAccess $xmlAccess): void
    {
        $this->setClassShortName($xmlAccess->getAttribute(self::CLASS_SHORT_NAME));
        $this->setNamespaceName($xmlAccess->getAttribute(self::NAMESPACE_NAME));
        $this->setTableName($xmlAccess->getAttribute(self::TABLE_NAME));
        $this->setIsDelimit($xmlAccess->getAttributeAsBool(self::DELIMIT));
        $this->setIsReplication($xmlAccess->getAttributeAsBool(self::REPLICATION));
        $this->setTargetPath($xmlAccess->getAttribute(self::TARGET_PATH));
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
     * @param $databaseName
     *
     * @return array
     */
    public function getDatabaseSpecificAttributeList($databaseName): array
    {
        if ($this->xmlAccess === null) {
            return [];
        }
        $tagName = self::ELEMENT_ENTITY_NAME . "-" . $databaseName;

        return $this->xmlAccess->getDatabaseSpecificAttributeList($tagName);
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
    public function getClassShortName(): ?string
    {
        return $this->classShortName;
    }


    /**
     * @param string|null $classShortName
     */
    public function setClassShortName(?string $classShortName): void
    {
        $this->classShortName = $classShortName;
    }


    /**
     * @return string|null
     */
    public function getNamespaceName(): ?string
    {
        return $this->namespaceName;
    }


    /**
     * @param string|null $namespaceName
     */
    public function setNamespaceName(?string $namespaceName = null): void
    {
        if ($namespaceName !== null) {
            $this->namespaceName = trim($namespaceName, "\\");
        }
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
    public function setTableName(?string $tableName): void
    {
        $this->tableName = $tableName;
    }


    /**
     * @return bool
     */
    public function getIsDelimit(): bool
    {
        return $this->isDelimit;
    }


    /**
     * @param bool $isDelimit
     */
    public function setIsDelimit(bool $isDelimit): void
    {
        $this->isDelimit = $isDelimit;
    }


    /**
     * @return string|null
     */
    public function getTargetPath(): ?string
    {
        return $this->targetPath;
    }


    /**
     * @param string|null $targetPath
     */
    public function setTargetPath(?string $targetPath): void
    {
        if ($targetPath !== null) {
            $this->targetPath = trim($targetPath, DIRECTORY_SEPARATOR);
        }
    }


    /**
     * @return bool
     */
    public function getIsReplication(): bool
    {
        return $this->isReplication;
    }


    /**
     * @param bool $isReplication
     */
    public function setIsReplication(bool $isReplication): void
    {
        $this->isReplication = $isReplication;
    }


    /**
     * @return XMLConstructor|null
     */
    public function getXmlConstructor(): ?XMLConstructor
    {
        return $this->xmlConstructor;
    }


    /**
     * @param XMLConstructor|null $xmlConstructor
     */
    public function setXmlConstructor(?XMLConstructor $xmlConstructor): void
    {
        $this->xmlConstructor = $xmlConstructor;
    }


    /**
     * @return XMLServiceClass|null
     */
    public function getXmlServiceClass(): ?XMLServiceClass
    {
        return $this->xmlServiceClass;
    }


    /**
     * @param XMLServiceClass|null $xmlServiceClass
     */
    public function setXmlServiceClass(?XMLServiceClass $xmlServiceClass): void
    {
        $this->xmlServiceClass = $xmlServiceClass;
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
     * @param bool $hasChanged
     */
    public function setHasChangedSinceLastGeneration(bool $hasChanged): void
    {
        $this->hasChangedSinceLastGeneration = $hasChanged;
    }


    /**
     * @return bool
     */
    public function hasChangedSinceLastGeneration(): bool
    {
        return $this->hasChangedSinceLastGeneration;
    }


}