<?php

namespace siestaphp\datamodel\entity;

use siestaphp\datamodel\attribute\Attribute;
use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\Collector;
use siestaphp\datamodel\collector\CollectorGeneratorSource;
use siestaphp\datamodel\collector\NMMapping;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\index\Index;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\manager\EntityManager;
use siestaphp\datamodel\manager\EntityManagerSource;
use siestaphp\datamodel\Processable;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\StoredProcedure;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\XMLEntity;
use siestaphp\util\File;

/**
 * Class Entity
 * @package siestaphp\datamodel
 */
class Entity implements Processable, EntitySource, EntityGeneratorSource
{

    const VALIDATION_ERROR_NO_CLASSNAME = 100;

    const VALIDATION_ERROR_INVALID_CLASSNAME = 101;

    const VALIDATION_ERROR_INVALID_NAMESPACE = 102;

    const VALIDATION_ERROR_INVALID_CONSTRUCT_CLASSNAME = 103;

    const VALIDATION_ERROR_INVALID_CONSTRUCT_NAMESPACE = 104;

    const VALIDATION_ERROR_NO_PRIMARY_KEY = 110;

    /**
     * @var EntitySource
     */
    protected $entitySource;

    /***
     * @var Attribute[]
     */
    protected $attributeList;

    /**
     * @var Reference[]
     */
    protected $referenceList;

    /**
     * @var Collector[]
     */
    protected $collectorList;

    /**
     * @var NMMapping[]
     */
    protected $neededNMLookupList;

    /**
     * @var StoredProcedure[]
     */
    protected $storedProcedureList;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string[]
     */
    protected $usedFQNClassNameList;

    /**
     * @var Index[]
     */
    protected $indexList;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $classNamespace;

    /**
     * @var string
     */
    protected $constructorClass;

    /**
     * @var string
     */
    protected $constructorNamespace;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var bool
     */
    protected $delimit;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * list of files from which this entity data is extracted
     * @var string[]
     */
    protected $fileNameList;

    /**
     *
     */
    public function __construct()
    {
        $this->neededNMLookupList = array();

        $this->attributeList = array();

        $this->referenceList = array();

        $this->collectorList = array();

        $this->storedProcedureList = array();

        $this->indexList = array();

        $this->usedFQNClassNameList = array();
    }

    /**
     * @param EntitySource $source
     *
     * @return void
     */
    public function setSource(EntitySource $source)
    {
        $this->entitySource = $source;

        $this->storeEntityData();

        $this->storeAttributeData();

        $this->storeReferenceData();

        $this->storeCollectorData();

        $this->storeStoredProcedureData();

        $this->storeIndexData();

        $this->deriveData();
    }

    /**
     * @return void
     */
    protected function storeEntityData()
    {
        $this->className = $this->entitySource->getClassName();
        $this->classNamespace = $this->entitySource->getClassNamespace();
        $this->constructorClass = $this->entitySource->getConstructorClass();
        $this->constructorNamespace = $this->entitySource->getConstructorNamespace();
        $this->table = $this->entitySource->getTable();
        $this->delimit = $this->entitySource->isDelimit();
        $this->targetPath = $this->entitySource->getTargetPath();

        $this->entityManager = new EntityManager($this, $this->entitySource->getEntityManagerSource());

    }

    /**
     * @return void
     */
    protected function storeAttributeData()
    {
        foreach ($this->entitySource->getAttributeSourceList() as $attributeSource) {
            $attribute = new Attribute();
            $attribute->setSource($attributeSource);
            $this->attributeList[] = $attribute;

        }
    }

    /**
     * @return void
     */
    protected function storeReferenceData()
    {
        foreach ($this->entitySource->getReferenceSourceList() as $referenceSource) {
            $reference = new Reference();
            $reference->setSource($this, $referenceSource);
            $this->referenceList[] = $reference;
        }
    }

    /**
     * @return void
     */
    protected function storeCollectorData()
    {
        foreach ($this->entitySource->getCollectorSourceList() as $collectorSource) {
            $collector = new Collector();
            $collector->setSource($this, $collectorSource);
            $this->collectorList[] = $collector;
        }
    }

    /**
     * @return void
     */
    protected function storeStoredProcedureData()
    {
        foreach ($this->entitySource->getStoredProcedureSourceList() as $storedProcedureSource) {
            $storedProcedure = new StoredProcedure();
            $storedProcedure->setSource($storedProcedureSource, $this);
            $this->storedProcedureList[] = $storedProcedure;
        }
    }

    /**
     * @return void
     */
    protected function storeIndexData()
    {
        foreach ($this->entitySource->getIndexSourceList() as $indexSource) {
            $this->indexList[] = new Index($this, $indexSource);
        }
    }

    /**
     * @return void
     */
    private function deriveData()
    {
        if (!$this->constructorClass) {
            $this->constructorClass = $this->className;
            $this->constructorNamespace = $this->classNamespace;
        }
        if (!$this->table) {
            $this->table = $this->className;
        }
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container)
    {

        foreach ($this->attributeList as $attribute) {
            $attribute->updateModel($container);

        }
        foreach ($this->referenceList as $reference) {
            $reference->updateModel($container);
        }

        foreach ($this->collectorList as $collector) {
            $collector->updateModel($container);
        }

        foreach ($this->indexList as $index) {
            $index->updateModel($container);
        }

        foreach ($this->storedProcedureList as $sp) {
            $sp->updateModel($container);
        }

        $this->assembleUsedClasses();
    }

    /**
     * creates a list with all classes that are need a use statement
     */
    private function assembleUsedClasses()
    {

        if ($this->constructorClass !== $this->className) {
            $this->addToUsedFQClassNames($this->getFullyQualifiedClassName());
        }

        if ($this->entitySource->getConstructFactoryFqn()) {
            $this->addToUsedFQClassNames($this->entitySource->getConstructFactoryFqn());
        }

        foreach ($this->referenceList as $reference) {
            $this->addToUsedFQClassNames($reference->getReferencedFullyQualifiedClassName());
        }

        foreach ($this->collectorList as $collector) {

            $this->addToUsedFQClassNames($collector->getReferencedFullyQualifiedClassName());
        }

    }

    /**
     * @param $fqClassName
     *
     * @return void
     */
    private function addToUsedFQClassNames($fqClassName)
    {
        if (!in_array($fqClassName, $this->usedFQNClassNameList)) {
            $this->usedFQNClassNameList[] = $fqClassName;
        }
    }

    /**
     * @param NMMapping $nmMapping
     */
    public function addNMMapping(NMMapping $nmMapping)
    {
        $this->neededNMLookupList[] = $nmMapping;
    }

    /**
     * @return NMMapping[]
     */
    public function getNMMappingSourceList()
    {
        return $this->neededNMLookupList;
    }

    /**
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(ValidationLogger $logger)
    {

        $this->validateEntity($logger);

        foreach ($this->attributeList as $attribute) {
            $attribute->validate($logger);
        }

        foreach ($this->referenceList as $reference) {
            $reference->validate($logger);
        }

        foreach ($this->collectorList as $collector) {
            $collector->validate($logger);
        }

        foreach ($this->indexList as $index) {
            $index->validate($logger);
        }

        foreach ($this->storedProcedureList as $sp) {
            $sp->validate($logger);
        }
    }

    /**
     * @param ValidationLogger $log
     *
     * @return void
     */
    private function validateEntity(ValidationLogger $log)
    {
        $log->info("Analyzing entity " . $this->className);

        $log->errorIfAttributeNotSet($this->className, XMLEntity::ATTRIBUTE_CLASS_NAME, XMLEntity::ELEMENT_ENTITY_NAME, self::VALIDATION_ERROR_NO_CLASSNAME);

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $this->className)) {
            $log->error($this->className . " is not a valid classname ", self::VALIDATION_ERROR_INVALID_CLASSNAME);
        }

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $this->constructorClass)) {
            $log->error($this->className . " is not a valid classname ", self::VALIDATION_ERROR_INVALID_CONSTRUCT_CLASSNAME);
        }

        $pkList = $this->getPrimaryKeyAttributeList();
        if (sizeof($pkList) === 0) {
            //$log->error("No <" . XMLEntity::ELEMENT_ENTITY_NAME . "> has " . XMLAttribute::ATTRIBUTE_PRIMARY_KEY . " true. A primary key is required", self::VALIDATION_ERROR_NO_PRIMARY_KEY);
        }
    }

    /**
     * @return Attribute[]
     */
    public function getPrimaryKeyAttributeList()
    {
        $resultList = array();
        foreach ($this->attributeList as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $resultList[] = $attribute;
            }
        }
        return $resultList;
    }

    /**
     * @return DatabaseColumn[]
     */
    public function getPrimaryKeyColumns()
    {
        $resultList = array();
        foreach ($this->attributeList as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $resultList[] = $attribute;
            }
        }
        foreach ($this->referenceList as $reference) {
            if ($reference->isPrimaryKey()) {
                $resultList = array_merge($resultList, $reference->getReferencedColumnList());
            }
        }
        return $resultList;
    }

    /**
     * @return bool
     */
    public function hasPrimaryKey()
    {
        return sizeof($this->getPrimaryKeyColumns()) !== 0;
    }

    /**
     * @param $name
     *
     * @return null|Attribute
     */
    public function getAttributeByName($name)
    {
        foreach ($this->attributeList as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @return AttributeSource[]
     */
    public function getAttributeSourceList()
    {
        return $this->attributeList;
    }

    /**
     * @return AttributeGeneratorSource[]
     */
    public function getAttributeGeneratorSourceList()
    {
        return $this->attributeList;
    }

    /**
     * @param string $name
     *
     * @return null|Reference
     */
    public function getReferenceByName($name)
    {
        foreach ($this->referenceList as $reference) {
            if ($reference->getName() === $name) {
                return $reference;
            }
        }
        return null;
    }

    /**
     * checks if this reference refers to the given column name
     *
     * @param string $name
     *
     * @return Reference|null
     */
    public function getReferenceByColumnName($name)
    {
        foreach ($this->referenceList as $reference) {
            if ($reference->getReferencedColumn($name) !== null) {
                return $reference;
            }
        }
        return null;
    }

    /**
     * @param $relationName
     *
     * @return null|Reference
     */
    public function getReferenceByRelationName($relationName)
    {
        if (!$relationName) {
            return null;
        }
        foreach ($this->referenceList as $reference) {
            if ($reference->getRelationName() === $relationName) {
                return $reference;
            }
        }
        return null;
    }

    /**
     * @return ReferenceSource[]
     */
    public function getReferenceSourceList()
    {
        return $this->referenceList;
    }

    /**
     * @return ReferenceGeneratorSource[]
     */
    public function getReferenceGeneratorSourceList()
    {
        return $this->referenceList;
    }

    /**
     * @return CollectorGeneratorSource[]
     */
    public function getCollectorSourceList()
    {
        return $this->collectorList;
    }

    /**
     * @return StoredProcedureSource[]
     */
    public function getStoredProcedureSourceList()
    {
        return $this->storedProcedureList;
    }

    /**
     * @return IndexSource[]
     */
    public function getIndexSourceList()
    {
        return $this->indexList;
    }

    /**
     * @return EntityManagerSource
     */
    public function getEntityManagerSource()
    {
        return $this->entityManager;
    }

    /**
     * @return bool
     */
    public function hasReferences()
    {
        return sizeof($this->referenceList) !== 0;
    }

    /**
     * @return bool
     */
    public function hasAttributes()
    {
        return sizeof($this->attributeList) !== 0;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
        return $this->classNamespace;
    }

    /**
     * @return string
     */
    public function getConstructorClass()
    {
        return $this->constructorClass;
    }

    /**
     * @return string
     */
    public function getConstructorNamespace()
    {
        return $this->constructorNamespace;
    }

    /**
     * @return string
     */
    public function getConstructFactory()
    {
        return $this->entitySource->getConstructFactory();
    }

    /**
     * @return string
     */
    public function getConstructFactoryFqn()
    {
        return $this->entitySource->getConstructFactoryFqn();
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getDelimitTable()
    {
        return $this->table . "_delimit";
    }

    /**
     * @return boolean
     */
    public function isDelimit()
    {
        return $this->delimit;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * checks if one attribute uses DateTime
     * @return bool
     */
    public function isDateTimeUsed()
    {
        if ($this->isDelimit()) {
            return true;
        }

        foreach ($this->attributeList as $attribute) {
            if ($attribute->isDateTime()) {
                return true;
            }
        }
        return false;
    }

    /**
     * assembles all used classes to prepare the use statement
     * @return string[]
     */
    public function getUsedFQClassNames()
    {
        return $this->usedFQNClassNameList;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName()
    {
        return $this->constructorNamespace . "\\" . $this->constructorClass;
    }

    /**
     * returns the target path for the all generated items (Entity, EntityDelimiter)
     *
     * @param $baseDir
     *
     * @return File
     */
    public function getAbsoluteTargetPath($baseDir)
    {
        if (!$this->targetPath) {
            return $baseDir . "/";
        }
        return new File($baseDir . "/" . $this->targetPath . "/");
    }

    /**
     * get the absolute file name for the entity file
     *
     * @param string $baseDir
     *
     * @return File
     */
    public function getTargetEntityFile($baseDir)
    {
        $path = $this->getAbsoluteTargetPath($baseDir);
        return new File($path->getAbsoluteFileName() . "/" . $this->className . ".php");
    }

    /**
     * @param $database
     *
     * @return DatabaseSpecificSource
     */
    public function getDatabaseSpecific($database)
    {
        return $this->entitySource->getDatabaseSpecific($database);
    }

    /**
     * @return DelimiterEntity
     */
    public function getDelimiterEntity()
    {
        $delimiterEntity = new DelimiterEntity();
        $delimiterEntity->setSource($this->entitySource);
        return $delimiterEntity;
    }
}