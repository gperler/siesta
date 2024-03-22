<?php

declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */

use ReflectionException;
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
    protected DataModel $dataModel;

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
    protected ?string $targetPath;

    /**
     * @var string|null
     */
    protected ?string $tableName;

    /**
     * @var bool
     */
    protected bool $isDelimit;

    /**
     * @var bool
     */
    protected bool $isReplication;

    /**
     * @var bool
     */
    protected bool $isDynamicCollectionTarget;

    /**
     * @var Constructor|null
     */
    protected ?Constructor $constructor;

    /**
     * @var ServiceClass|null
     */
    protected ?ServiceClass $serviceClass;

    /**
     * @var Attribute[]
     */
    protected array $attributeList;

    /**
     * @var Reference[]
     */
    protected array $referenceList;

    /**
     * @var Index[]
     */
    protected array $indexList;

    /**
     * @var Collection[]
     */
    protected array $collectionList;

    /**
     * @var CollectionMany[]
     */
    protected array $collectionManyList;

    /**
     * @var DynamicCollection[]
     */
    protected array $dynamicCollectionList;

    /**
     * @var StoredProcedure[]
     */
    protected array $storedProcedureList;

    /**
     * @var ValueObject[]
     */
    protected array $valueObjectList;

    /**
     * @var CollectionMany[]
     */
    protected array $foreignCollectionManyList;

    /**
     * @var XMLEntity|null
     */
    protected ?XMLEntity $xmlEntity;

    /**
     * @var string[]
     */
    protected array $customAttributeList;


    /**
     * @var bool
     */
    protected bool $hasChangedSinceLastGeneration;


    /**
     * Entity constructor.
     *
     * @param DataModel $dataModel
     */
    public function __construct(DataModel $dataModel)
    {
        $this->dataModel = $dataModel;
        $this->classShortName = null;
        $this->namespaceName = null;
        $this->targetPath = null;
        $this->tableName = null;
        $this->isDelimit = false;
        $this->isReplication = false;
        $this->isDynamicCollectionTarget = false;
        $this->constructor = null;
        $this->serviceClass = null;
        $this->attributeList = [];
        $this->referenceList = [];
        $this->indexList = [];
        $this->collectionList = [];
        $this->collectionManyList = [];
        $this->dynamicCollectionList = [];
        $this->storedProcedureList = [];
        $this->valueObjectList = [];
        $this->foreignCollectionManyList = [];
        $this->xmlEntity = null;
        $this->customAttributeList = [];
        $this->hasChangedSinceLastGeneration = true;
    }


    /**
     * @return bool
     */
    public function hasChangedSinceLastGeneration(): bool
    {
        return $this->hasChangedSinceLastGeneration;
    }


    /**
     * @param bool $hasChangedSinceLastGeneration
     */
    public function setHasChangedSinceLastGeneration(?bool $hasChangedSinceLastGeneration): void
    {
        $this->hasChangedSinceLastGeneration = $hasChangedSinceLastGeneration;
    }


    /**
     * @return Constructor
     */
    public function newConstructor(): Constructor
    {
        $this->constructor = new Constructor();
        return $this->constructor;
    }


    /**
     * @return ServiceClass
     */
    public function newServiceClass(): ServiceClass
    {
        $this->serviceClass = new ServiceClass();
        return $this->serviceClass;
    }


    /**
     * @return Attribute
     */
    public function newAttribute(): Attribute
    {
        $attribute = new Attribute($this);
        $this->attributeList[] = $attribute;
        return $attribute;
    }


    /**
     * @return Reference
     */
    public function newReference(): Reference
    {
        $reference = new Reference($this->dataModel, $this);
        $this->referenceList[] = $reference;
        return $reference;
    }


    /**
     * @return Index
     */
    public function newIndex(): Index
    {
        $index = new Index($this);
        $this->indexList[] = $index;
        return $index;
    }


    /**
     * @return Collection
     */
    public function newCollection(): Collection
    {
        $collection = new Collection($this->dataModel, $this);
        $this->collectionList[] = $collection;
        return $collection;
    }


    /**
     * @return CollectionMany
     */
    public function newCollectionMany(): CollectionMany
    {
        $collectionMany = new CollectionMany($this->dataModel, $this);
        $this->collectionManyList[] = $collectionMany;
        return $collectionMany;
    }


    /**
     * @return DynamicCollection
     */
    public function newDynamicCollection(): DynamicCollection
    {
        $dynamicCollection = new DynamicCollection($this->dataModel, $this);
        $this->dynamicCollectionList[] = $dynamicCollection;
        return $dynamicCollection;
    }


    /**
     * @return StoredProcedure
     */
    public function newStoredProcedure(): StoredProcedure
    {
        $storedProcedure = new StoredProcedure($this);
        $this->storedProcedureList[] = $storedProcedure;
        return $storedProcedure;
    }


    /**
     * @return ValueObject
     */
    public function newValueObject(): ValueObject
    {
        $valueObject = new ValueObject($this);
        $this->valueObjectList[] = $valueObject;
        return $valueObject;
    }


    /**
     * @throws ReflectionException
     */
    public function update(): void
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
        foreach ($this->dynamicCollectionList as $dynamicCollection) {
            $dynamicCollection->update();
        }
        foreach ($this->valueObjectList as $valueObject) {
            $valueObject->update();
        }
    }


    public function setIsDynamicCollectionTarget(): void
    {
        if ($this->isDynamicCollectionTarget) {
            return;
        }
        $this->isDynamicCollectionTarget = true;

        $this->attributeList = array_merge($this->attributeList, DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this));
    }


    /**
     * @return bool
     */
    public function getIsDynamicCollectionTarget(): bool
    {
        return $this->isDynamicCollectionTarget;
    }


    /**
     * @return Attribute[]
     */
    public function getPrimaryKeyAttributeList(): array
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
    public function hasPrimaryKey(): bool
    {
        foreach ($this->attributeList as $attribute) {
            if ($attribute->getIsPrimaryKey()) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param string|null $name
     *
     * @return null|Attribute
     */
    public function getAttributeByName(string $name = null): ?Attribute
    {
        foreach ($this->attributeList as $attribute) {
            if ($attribute->getPhpName() === $name) {
                return $attribute;
            }
        }
        return null;
    }


    /**
     * @param string|null $name
     *
     * @return null|Reference
     */
    public function getReferenceByName(string $name = null): ?Reference
    {
        foreach ($this->referenceList as $reference) {
            if ($reference->getName() === $name) {
                return $reference;
            }
        }
        return null;
    }


    /**
     * @param string|null $name
     *
     * @return null|Index
     */
    public function getIndexByName(string $name = null): ?Index
    {
        foreach ($this->indexList as $index) {
            if ($index->getName() === $name) {
                return $index;
            }
        }
        return null;
    }


    /**
     * @param string|null $name
     *
     * @return null|Collection
     */
    public function getCollectionByName(string $name = null): ?Collection
    {
        foreach ($this->collectionList as $collection) {
            if ($collection->getName() === $name) {
                return $collection;
            }
        }
        return null;
    }


    /**
     * @param string|null $name
     *
     * @return null|CollectionMany
     */
    public function getCollectionManyByName(string $name = null): ?CollectionMany
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
    public function getAttributeList(): array
    {
        return $this->attributeList;
    }


    /**
     * @return Reference[]
     */
    public function getReferenceList(): array
    {
        return $this->referenceList;
    }


    /**
     * @return Index[]
     */
    public function getIndexList(): array
    {
        return $this->indexList;
    }


    /**
     * @return Collection[]
     */
    public function getCollectionList(): array
    {
        return $this->collectionList;
    }


    /**
     * @return CollectionMany[]
     */
    public function getCollectionManyList(): array
    {
        return $this->collectionManyList;
    }


    /**
     * @return DynamicCollection[]
     */
    public function getDynamicCollectionList(): array
    {
        return $this->dynamicCollectionList;
    }


    /**
     * @return StoredProcedure[]
     */
    public function getStoredProcedureList(): array
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
     * @param string|null $name
     *
     * @return null|StoredProcedure
     */
    public function getStoredProcedureByName(string $name = null): ?StoredProcedure
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
    public function getDatabaseSpecificAttributeList(string $databaseName): array
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
    public function getCustomAttribute(string $name): ?string
    {
        return ArrayUtil::getFromArray($this->customAttributeList, $name);
    }


    /**
     * @return string[]
     */
    public function getCustomAttributeList(): array
    {
        return $this->customAttributeList;
    }


    /**
     * @param string[] $customAttributeList
     */
    public function setCustomAttributeList(array $customAttributeList): void
    {
        $this->customAttributeList = $customAttributeList;
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getNamespaceName() . "\\" . $this->getClassShortName();
    }


    /**
     * @return string
     */
    public function getClassShortName(): ?string
    {
        return $this->classShortName;
    }


    /**
     * @param string|null $classShortName
     */
    public function setClassShortName(string $classShortName = null): void
    {
        $this->classShortName = $classShortName;
    }


    /**
     * @return string
     */
    public function getServiceClassShortName(): string
    {
        return $this->getClassShortName() . self::SERVICE_CLASS_SUFFIX;
    }


    /**
     * @return string
     */
    public function getServiceClassName(): string
    {
        if ($this->serviceClass === null) {
            return $this->getClassName() . self::SERVICE_CLASS_SUFFIX;
        }
        return $this->serviceClass->getClassName();
    }


    /**
     * @return string
     */
    public function getServiceGenerationClassName(): string
    {
        return $this->getClassName() . self::SERVICE_CLASS_SUFFIX;
    }


    /**
     * @return string
     */
    public function getServiceGenerationClassNameShort(): string
    {
        return $this->getClassShortName() . self::SERVICE_CLASS_SUFFIX;
    }


    /**
     * @return string
     */
    public function getTargetPath(): string
    {
        if ($this->targetPath !== null) {
            return $this->targetPath;
        }
        return str_replace("\\", DIRECTORY_SEPARATOR, $this->getNamespaceName());
    }


    /**
     * @param string|null $targetPath
     */
    public function setTargetPath(string $targetPath = null): void
    {
        if ($targetPath !== null) {
            $this->targetPath = trim($targetPath, DIRECTORY_SEPARATOR);
        }
    }


    /**
     * @return string
     */
    public function getNamespaceName(): ?string
    {
        return $this->namespaceName;
    }


    /**
     * @param string|null $namespaceName
     */
    public function setNamespaceName(string $namespaceName = null): void
    {
        if ($namespaceName !== null) {
            $this->namespaceName = trim($namespaceName, "\\");
        }
    }


    /**
     * @return string
     */
    public function getTableName(): ?string
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
    public function getDelimitTableName(): string
    {
        return $this->getTableName() . self::DELIMIT_SUFFIX;
    }


    /**
     * @param string|null $tableName
     */
    public function setTableName(string $tableName = null): void
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
    public function setIsDelimit(bool $isDelimit = false): void
    {
        $this->isDelimit = $isDelimit;
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
    public function setIsReplication(bool $isReplication = false): void
    {
        $this->isReplication = $isReplication;
    }


    /**
     * @return string
     */
    public function getReplicationTableName(): string
    {
        return $this->getTableName() . self::REPLICATION_TABLE_SUFFIX;
    }


    /**
     * @return Constructor|null
     */
    public function getConstructor(): ?Constructor
    {
        return $this->constructor;
    }


    /**
     * @param Constructor|null $constructor
     */
    public function setConstructor(Constructor $constructor = null): void
    {
        $this->constructor = $constructor;
    }


    /**
     * @return ServiceClass|null
     */
    public function getServiceClass(): ?ServiceClass
    {
        return $this->serviceClass;
    }


    /**
     * @param ServiceClass|null $serviceClass
     */
    public function setServiceClass(ServiceClass $serviceClass = null): void
    {
        $this->serviceClass = $serviceClass;
    }


    /**
     * @return string
     */
    public function getInstantiationClassShortName(): string
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
     * @return string
     */
    public function getInstantiationClassName(): string
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
    public function getServiceClassInstantiationClassName(): string
    {
        if ($this->serviceClass === null) {
            return $this->getServiceGenerationClassName();
        }
        return $this->serviceClass->getClassName();
    }


    /**
     * @return null|string
     */
    public function getServiceClassInstantiationClassNameShort(): ?string
    {
        if ($this->serviceClass === null) {
            return $this->getServiceGenerationClassNameShort();
        }
        return $this->serviceClass->getClassShortName();
    }


    /**
     * @return string
     */
    public function getServiceAccess(): string
    {
        if ($this->serviceClass !== null && $this->serviceClass->getConstructCall() !== null) {
            return $this->serviceClass->getConstructCall();
        }
        return $this->getServiceClassShortName() . '::' . SingletonPlugin::METHOD_SINGLETON . '()';
    }


    /**
     * @return null|string
     */
    public function getServiceFactoryClass(): ?string
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
    public function addForeignCollectionManyList(CollectionMany $collectionMany): void
    {
        $this->foreignCollectionManyList[] = $collectionMany;
    }


    /**
     * @param XMLEntity $xmlEntity
     */
    public function setXmlEntity(XMLEntity $xmlEntity): void
    {
        $this->xmlEntity = $xmlEntity;
    }


    /**
     * @param string $childName
     *
     * @return XMLAccess[]
     */
    public function getXMLChildElementListByName(string $childName): array
    {
        return $this->xmlEntity->getXMLChildElementListByName($childName);
    }

}