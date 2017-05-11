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
     * @var string
     */
    protected $classShortName;

    /**
     * @var string
     */
    protected $namespaceName;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var bool
     */
    protected $isDelimit;

    /**
     * @var bool;
     */
    protected $isReplication;

    /**
     * @var string
     */
    protected $targetPath;

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
     * @var array
     */
    protected $databaseSpecific;

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
        $this->databaseSpecific = [];
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
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
    protected function databaseSpecificToXML(XMLWrite $parent)
    {
        if ($this->databaseSpecific === null) {
            return;
        }
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
    public function fromTable(TableMetaData $table)
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
    public function fromXML(XMLAccess $xmlAccess)
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
     * @param XMLAccess $xmlAccess
     */
    protected function readEntityDataFromXML(XMLAccess $xmlAccess)
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
     * @param string $childname
     *
     * @return XMLAccess[]
     */
    public function getXMLChildElementListByName(string $childname)
    {
        return $this->xmlAccess->getXMLChildElementListByName($childname);
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
    public function getDatabaseSpecificAttributeList($databaseName)
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
    public function getClassShortName()
    {
        return $this->classShortName;
    }

    /**
     * @param string $classShortName
     */
    public function setClassShortName($classShortName)
    {
        $this->classShortName = $classShortName;
    }

    /**
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * @param string $namespaceName
     */
    public function setNamespaceName(string $namespaceName = null)
    {
        if ($namespaceName !== null) {
            $this->namespaceName = trim($namespaceName, "\\");
        }
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
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return bool
     */
    public function getIsDelimit()
    {
        return $this->isDelimit;
    }

    /**
     * @param bool $isDelimit
     */
    public function setIsDelimit($isDelimit)
    {
        $this->isDelimit = $isDelimit;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * @param string $targetPath
     */
    public function setTargetPath($targetPath)
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
    public function setIsReplication($isReplication)
    {
        $this->isReplication = $isReplication;
    }

    /**
     * @return XMLConstructor
     */
    public function getXmlConstructor()
    {
        return $this->xmlConstructor;
    }

    /**
     * @param XMLConstructor $xmlConstructor
     */
    public function setXmlConstructor($xmlConstructor)
    {
        $this->xmlConstructor = $xmlConstructor;
    }

    /**
     * @return XMLServiceClass
     */
    public function getXmlServiceClass()
    {
        return $this->xmlServiceClass;
    }

    /**
     * @param XMLServiceClass $xmlServiceClass
     */
    public function setXmlServiceClass($xmlServiceClass)
    {
        $this->xmlServiceClass = $xmlServiceClass;
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

}