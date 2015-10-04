<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 14:43
 */

namespace siestaphp\datamodel\entity;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\Attribute;
use siestaphp\datamodel\attribute\AttributeDatabaseSource;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\Collector;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\index\Index;
use siestaphp\datamodel\index\IndexDatabaseSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\Processable;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\StoredProcedure;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\generator\GeneratorLog;
use siestaphp\naming\XMLAttribute;
use siestaphp\naming\XMLEntity;
use siestaphp\util\File;

/**
 * Class Entity
 * @package siestaphp\datamodel
 */
class Entity implements Processable, EntitySource, EntityTransformerSource, EntityDatabaseSource
{

    /**
     * @var EntityDatabaseSource
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
     * @var StoredProcedure[]
     */
    protected $storedProcedureList;

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
        $this->attributeList = array();

        $this->referenceList = array();

        $this->collectorList = array();

        $this->storedProcedureList = array();

        $this->indexList = array();

        $this->usedFQNClassNameList = array();
    }

    /**
     * @param EntitySource $source
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

    private function storeEntityData()
    {
        $this->className = $this->entitySource->getClassName();
        $this->classNamespace = $this->entitySource->getClassNamespace();
        $this->constructorClass = $this->entitySource->getConstructorClass();
        $this->constructorNamespace = $this->entitySource->getConstructorNamespace();
        $this->table = $this->entitySource->getTable();
        $this->delimit = $this->entitySource->isDelimit();
        $this->targetPath = $this->entitySource->getTargetPath();
    }

    /**
     *
     */
    private function storeAttributeData()
    {
        foreach ($this->entitySource->getAttributeSourceList() as $attributeSource) {
            $attribute = new Attribute();
            $attribute->setSource($attributeSource);
            $this->attributeList[] = $attribute;

        }
    }

    private function storeReferenceData()
    {
        foreach ($this->entitySource->getReferenceSourceList() as $referenceSource) {
            $reference = new Reference();
            $reference->setSource($this, $referenceSource);
            $this->referenceList[] = $reference;
        }
    }

    private function storeCollectorData()
    {
        foreach ($this->entitySource->getCollectorSourceList() as $collectorSource) {
            $collector = new Collector();
            $collector->setSource($collectorSource);
            $this->collectorList[] = $collector;
        }
    }

    private function storeStoredProcedureData()
    {
        foreach ($this->entitySource->getStoredProcedureSourceList() as $storedProcedureSource) {
            $storedProcedure = new StoredProcedure();
            $storedProcedure->setSource($storedProcedureSource, $this);
            $this->storedProcedureList[] = $storedProcedure;
        }
    }

    private function storeIndexData()
    {
        foreach ($this->entitySource->getIndexSourceList() as $indexSource) {
            $this->indexList[] = new Index($this, $indexSource);
        }
    }

    private function deriveData()
    {
        if (!$this->constructorClass) {
            $this->constructorClass = $this->className;
            $this->constructorNamespace = $this->classNamespace;
        }
    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        if ($this->constructorClass !== $this->className) {
            $this->usedFQNClassNameList[] = $this->getFullyQualifiedClassName();
        }

        foreach ($this->attributeList as $attribute) {
            $attribute->updateModel($container);

        }
        foreach ($this->referenceList as $reference) {
            $reference->updateModel($container);
            $this->addToUsedFQClassNames($reference->getReferencedFullyQualifiedClassName());
        }
        foreach ($this->collectorList as $collector) {
            $collector->updateModel($container);
            $this->addToUsedFQClassNames($collector->getReferencedFullyQualifiedClassName());
        }

        foreach ($this->indexList as $index) {
            $index->updateModel($container);
        }
    }

    /**
     * @param $fqClassName
     */
    private function addToUsedFQClassNames($fqClassName)
    {
        if (!in_array($fqClassName, $this->usedFQNClassNameList)) {
            $this->usedFQNClassNameList[] = $fqClassName;
        }
    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {

        $this->validateEntity($log);

        foreach ($this->attributeList as $attribute) {
            $attribute->validate($log);
        }

        foreach ($this->referenceList as $reference) {
            $reference->validate($log);
        }

        foreach ($this->collectorList as $collector) {
            $collector->validate($log);
        }

        foreach ($this->indexList as $index) {
            $index->validate($log);
        }
    }

    /**
     * @param GeneratorLog $log
     */
    private function validateEntity(GeneratorLog $log)
    {
        $log->info("Analyzing entity " . $this->className);

        $log->errorIfAttributeNotSet($this->className, XMLEntity::ATTRIBUTE_CLASS_NAME, XMLEntity::ELEMENT_ENTITY_NAME);

        $log->errorIfAttributeNotSet($this->table, XMLEntity::ATTRIBUTE_TABLE, XMLEntity::ELEMENT_ENTITY_NAME);

        $pkList = $this->getPrimaryKeyAttributeList();
        if (sizeof($pkList) === 0) {
            $log->error("No <" . XMLEntity::ELEMENT_ENTITY_NAME . "> has " . XMLAttribute::ATTRIBUTE_PRIMARY_KEY . " true. A primary key is required");
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
     * @return AttributeDatabaseSource[]
     */
    public function getAttributeDatabaseSourceList()
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
     * @return ReferenceDatabaseSource[]
     */
    public function getReferenceDatabaseSourceList()
    {
        return $this->referenceList;
    }

    /**
     * @return CollectorSource[]
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
     * @return IndexDatabaseSource[]
     */
    public function getIndexDatabaseSourceList()
    {
        return $this->indexList;
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
    public function getTable()
    {
        return $this->table;
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
     * builds the find by primary key signature (might have multiple primary keys)
     * @return string
     */
    public function getFindByPKSignature()
    {
        $signature = '';

        foreach ($this->attributeList as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $signature .= "$" . $attribute->getName() . ",";
            }
        }

        return rtrim($signature, ",");
    }

    /**
     * @return string
     */
    public function getSPCallSignature()
    {
        $signature = '';

        foreach ($this->attributeList as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $signature .= "'$" . $attribute->getName() . "',";
            }
        }

        return rtrim($signature, ",");
    }

}