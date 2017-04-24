<?php

declare(strict_types = 1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
use Siesta\GeneratorPlugin\ServiceClass\SingletonPlugin;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\Util\ArrayUtil;
use Siesta\XML\XMLAccess;
use Siesta\XML\XMLEntity;

class Entity
{

    const SERVICE_CLASS_SUFFIX = "Service";

    const DELIMIT_SUFFIX = "_delimit";

    const REPLICATION_TABLE_SUFFIX = "_memory";

    /**
     * @var DataModel
     */
    protected $dataModel;

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
    protected $targetPath;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var bool
     */
    protected $isDelimit;

    /**
     * @var bool
     */
    protected $isReplication;

    /**
     * @var Constructor
     */
    protected $constructor;

    /**
     * @var ServiceClass
     */
    protected $serviceClass;

    /**
     * @var Attribute[]
     */
    protected $attributeList;

    /**
     * @var Reference[]
     */
    protected $referenceList;

    /**
     * @var Index[]
     */
    protected $indexList;

    /**
     * @var Collection[]
     */
    protected $collectionList;

    /**
     * @var CollectionMany[]
     */
    protected $collectionManyList;

    /**
     * @var StoredProcedure[]
     */
    protected $storedProcedureList;

    /**
     * @var ValueObject[]
     */
    protected $valueObjectList;

    /**
     * @var CollectionMany[]
     */
    protected $foreignCollectionManyList;

    /**
     * @var XMLEntity
     */
    protected $xmlEntity;

    /**
     * @var string[]
     */
    protected $customAttributeList;

    /**
     * Entity constructor.
     *
     * @param DataModel $dataModel
     */
    public function __construct(DataModel $dataModel)
    {
        $this->dataModel = $dataModel;
        $this->attributeList = [];
        $this->referenceList = [];
        $this->indexList = [];
        $this->collectionList = [];
        $this->collectionManyList = [];
        $this->foreignCollectionManyList = [];
        $this->storedProcedureList = [];
        $this->valueObjectList = [];
    }

    /**
     * @return Constructor
     */
    public function newConstructor() : Constructor
    {
        $this->constructor = new Constructor();
        return $this->constructor;
    }

    /**
     * @return ServiceClass
     */
    public function newServiceClass() : ServiceClass
    {
        $this->serviceClass = new ServiceClass();
        return $this->serviceClass;
    }

    /**
     * @return Attribute
     */
    public function newAttribute() : Attribute
    {
        $attribute = new Attribute($this);
        $this->attributeList[] = $attribute;
        return $attribute;
    }

    /**
     * @return Reference
     */
    public function newReference() : Reference
    {
        $reference = new Reference($this->dataModel, $this);
        $this->referenceList[] = $reference;
        return $reference;
    }

    /**
     * @return Index
     */
    public function newIndex() : Index
    {
        $index = new Index($this);
        $this->indexList[] = $index;
        return $index;
    }

    /**
     * @return Collection
     */
    public function newCollection() : Collection
    {
        $collection = new Collection($this->dataModel, $this);
        $this->collectionList[] = $collection;
        return $collection;
    }

    /**
     * @return CollectionMany
     */
    public function newCollectionMany() : CollectionMany
    {
        $collectionMany = new CollectionMany($this->dataModel, $this);
        $this->collectionManyList[] = $collectionMany;
        return $collectionMany;
    }

    /**
     * @return StoredProcedure
     */
    public function newStoredProcedure() : StoredProcedure
    {
        $storedProcedure = new StoredProcedure($this);
        $this->storedProcedureList[] = $storedProcedure;
        return $storedProcedure;
    }

    /**
     * @return ValueObject
     */
    public function newValueObject() : ValueObject
    {
        $valueObject = new ValueObject($this);
        $this->valueObjectList[] = $valueObject;
        return $valueObject;
    }

    /**
     *
     */
    public function update()
    {
        foreach ($this->attributeList as $attribute) {
            $attribute->update();
        }
        foreach ($this->referenceList as $reference) {
            $reference->update();
        }
        foreach ($this->indexList as $index) {
            $index->update();
        }
        foreach ($this->collectionList as $collection) {
            $collection->update();
        }
        foreach ($this->collectionManyList as $collectionMany) {
            $collectionMany->update();
        }

        foreach ($this->valueObjectList as $valueObject) {
            $valueObject->update();
        }

    }

    /**
     * @return Attribute[]
     */
    public function getPrimaryKeyAttributeList() : array
    {
        $primaryKeyAttributeList = [];
        foreach ($this->attributeList as $attribute) {
            if ($attribute->getIsPrimaryKey()) {
                $primaryKeyAttributeList[] = $attribute;
            }
        }
        return $primaryKeyAttributeList;
    }

    /**
     * @return bool
     */
    public function hasPrimaryKey() : bool
    {
        foreach ($this->attributeList as $attribute) {
            if ($attribute->getIsPrimaryKey()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $name
     *
     * @return null|Attribute
     */
    public function getAttributeByName(string $name = null)
    {
        foreach ($this->attributeList as $attribute) {
            if ($attribute->getPhpName() === $name) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @param string $name
     *
     * @return null|Reference
     */
    public function getReferenceByName(string $name = null)
    {
        foreach ($this->referenceList as $reference) {
            if ($reference->getName() === $name) {
                return $reference;
            }
        }
        return null;
    }

    /**
     * @param string $name
     *
     * @return null|Index
     */
    public function getIndexByName(string $name = null)
    {
        foreach ($this->indexList as $index) {
            if ($index->getName() === $name) {
                return $index;
            }
        }
        return null;
    }

    /**
     * @param string $name
     *
     * @return null|Collection
     */
    public function getCollectionByName(string $name = null)
    {
        foreach ($this->collectionList as $collection) {
            if ($collection->getName() === $name) {
                return $collection;
            }
        }
        return null;
    }

    /**
     * @param string $name
     *
     * @return null|CollectionMany
     */
    public function getCollectionManyByName(string $name = null)
    {
        foreach ($this->collectionManyList as $collectionMany) {
            if ($collectionMany->getName() === $name) {
                return $collectionMany;
            }
        }
        return null;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributeList() : array
    {
        return $this->attributeList;
    }

    /**
     * @return Reference[]
     */
    public function getReferenceList() : array
    {
        return $this->referenceList;
    }

    /**
     * @return Index[]
     */
    public function getIndexList() : array
    {
        return $this->indexList;
    }

    /**
     * @return Collection[]
     */
    public function getCollectionList() : array
    {
        return $this->collectionList;
    }

    /**
     * @return CollectionMany[]
     */
    public function getCollectionManyList() : array
    {
        return $this->collectionManyList;
    }

    /**
     * @return StoredProcedure[]
     */
    public function getStoredProcedureList() : array
    {
        return $this->storedProcedureList;
    }

    /**
     * @return ValueObject[]
     */
    public function getValueObjectList(): array
    {
        return $this->valueObjectList;
    }

    /**
     * @param string $name
     *
     * @return null|StoredProcedure
     */
    public function getStoredProcedureByName(string $name = null)
    {
        foreach ($this->storedProcedureList as $storedProcedure) {
            if ($storedProcedure->getName() === $name) {
                return $storedProcedure;
            }
        }
        return null;
    }

    /**
     * @param string $databaseName
     *
     * @return array
     */
    public function getDatabaseSpecificAttributeList(string $databaseName)
    {
        if ($this->xmlEntity === null) {
            return [];
        }
        return $this->xmlEntity->getDatabaseSpecificAttributeList($databaseName);
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getCustomAttribute(string $name)
    {
        return ArrayUtil::getFromArray($this->customAttributeList, $name);
    }

    /**
     * @return \string[]
     */
    public function getCustomAttributeList(): array
    {
        return $this->customAttributeList;
    }

    /**
     * @param \string[] $customAttributeList
     */
    public function setCustomAttributeList(array $customAttributeList)
    {
        $this->customAttributeList = $customAttributeList;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getNamespaceName() . "\\" . $this->getClassShortName();
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
    public function setClassShortName(string $classShortName = null)
    {
        $this->classShortName = $classShortName;
    }

    /**
     * @return string
     */
    public function getServiceClassShortName()
    {
        return $this->getClassShortName() . self::SERVICE_CLASS_SUFFIX;
    }

    /**
     * @return string
     */
    public function getServiceClassName()
    {
        if ($this->serviceClass === null) {
            return $this->getClassName() . self::SERVICE_CLASS_SUFFIX;
        }
        return $this->serviceClass->getClassName();
    }

    /**
     * @return string
     */
    public function getServiceGenerationClassName()
    {
        return $this->getClassName() . self::SERVICE_CLASS_SUFFIX;
    }

    /**
     * @return string
     */
    public function getServiceGenerationClassNameShort()
    {
        return $this->getClassShortName() . self::SERVICE_CLASS_SUFFIX;
    }

    /**
     * @return string
     */
    public function getTargetPath() : string
    {
        if ($this->targetPath !== null) {
            return $this->targetPath;
        }
        return str_replace("\\", DIRECTORY_SEPARATOR, $this->getNamespaceName());
    }

    /**
     * @param string $targetPath
     */
    public function setTargetPath(string $targetPath = null)
    {
        if ($targetPath !== null) {
            $this->targetPath = trim($targetPath, DIRECTORY_SEPARATOR);
        }
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
        if ($this->tableName !== null) {
            return $this->tableName;
        }
        $strategy = NamingStrategyRegistry::getTableNamingStrategy();
        return $strategy->transform($this->getClassShortName());
    }

    /**
     * @return string
     */
    public function getDelimitTableName()
    {
        return $this->getTableName() . self::DELIMIT_SUFFIX;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName = null)
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
    public function setIsDelimit(bool $isDelimit = false)
    {
        $this->isDelimit = $isDelimit;
    }

    /**
     * @return bool
     */
    public function getIsReplication() : bool
    {
        return $this->isReplication;
    }

    /**
     * @param bool $isReplication
     */
    public function setIsReplication(bool $isReplication = false)
    {
        $this->isReplication = $isReplication;
    }

    /**
     * @return string
     */
    public function getReplicationTableName() : string
    {
        return $this->getTableName() . self::REPLICATION_TABLE_SUFFIX;
    }

    /**
     * @return Constructor
     */
    public function getConstructor()
    {
        return $this->constructor;
    }

    /**
     * @param Constructor $constructor
     */
    public function setConstructor(Constructor $constructor = null)
    {
        $this->constructor = $constructor;
    }

    /**
     * @return ServiceClass
     */
    public function getServiceClass()
    {
        return $this->serviceClass;
    }

    /**
     * @param ServiceClass $serviceClass
     */
    public function setServiceClass(ServiceClass $serviceClass = null)
    {
        $this->serviceClass = $serviceClass;
    }

    /**
     * @return string
     */
    public function getInstantiationClassShortName() : string
    {
        if ($this->constructor === null) {
            return $this->getClassShortName();
        }

        if ($this->constructor->getClassShortName() !== null) {
            return $this->constructor->getClassShortName();
        }

        return $this->getClassShortName();
    }

    /**
     * @return string|null
     */
    public function getInstantiationClassName() : string
    {
        if ($this->constructor === null) {
            return $this->getClassName();
        }
        if ($this->constructor->getClassShortName() !== null) {
            return $this->constructor->getClassName();
        }
        return $this->getClassName();
    }

    /**
     * @return string
     */
    public function getServiceClassInstantiationClassName()
    {
        if ($this->serviceClass === null) {
            return $this->getServiceGenerationClassName();
        }
        return $this->serviceClass->getClassName();
    }

    /**
     * @return null|string
     */
    public function getServiceClassInstantiationClassNameShort()
    {
        if ($this->serviceClass === null) {
            return $this->getServiceGenerationClassNameShort();
        }
        return $this->serviceClass->getClassShortName();
    }

    /**
     * @return string
     */
    public function getServiceAccess() : string
    {
        if ($this->serviceClass !== null && $this->serviceClass->getConstructCall() !== null) {
            return $this->serviceClass->getConstructCall();
        }
        return $this->getServiceClassShortName() . '::' . SingletonPlugin::METHOD_SINGLETON . '()';
    }

    /**
     * @return null|string
     */
    public function getServiceFactoryClass()
    {
        if ($this->serviceClass !== null) {
            return $this->serviceClass->getConstructFactoryClassName();
        }
        return null;
    }

    /**
     * @return CollectionMany[]
     */
    public function getForeignCollectionManyList(): array
    {
        return $this->foreignCollectionManyList;
    }

    /**
     * @param CollectionMany $collectionMany
     */
    public function addForeignCollectionManyList(CollectionMany $collectionMany)
    {
        $this->foreignCollectionManyList[] = $collectionMany;
    }

    /**
     * @param XMLEntity $xmlEntity
     */
    public function setXmlEntity(XMLEntity $xmlEntity)
    {
        $this->xmlEntity = $xmlEntity;
    }

    /**
     * @param string $childName
     *
     * @return XMLAccess[]
     */
    public function get(string $childName) {
        return $this->xmlEntity->getXMLChildElementListByName($childName);
    }

}