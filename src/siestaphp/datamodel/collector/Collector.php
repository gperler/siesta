<?php

namespace siestaphp\datamodel\collector;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\generator\ValidationLogger;

/**
 * Class Collector
 * @package siestaphp\datamodel
 */
class Collector implements Processable, CollectorSource, CollectorGeneratorSource
{
    const ONE_N = "1n";

    const N_M = "nm";

    const VALIDATION_ERROR_INVALID_NAME = 400;

    const VALIDATION_ERROR_INVALID_ENTITY_REFERENCED = 401;

    const VALIDATION_ERROR_INVALID_REFERENCE = 402;

    const VALIDATION_ERROR_INVALID_MAPPING_CLASS = 403;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var CollectorSource
     */
    protected $collectorSource;

    /**
     * @var Entity
     */
    protected $foreignClassEntity;

    /**
     * @var Entity
     */
    protected $mappingClassEntity;

    /**
     * @var NMMapping
     */
    protected $nmMapping;

    /**
     * @var Reference
     */
    protected $reference;

    /**
     * @var CollectorFilter[]
     */
    protected $collectorFilterList;

    /**
     * @param Entity $entity
     * @param CollectorSource $source
     */
    public function setSource(Entity $entity, CollectorSource $source)
    {
        $this->entity = $entity;
        $this->collectorSource = $source;
        $this->collectorFilterList = [];
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container)
    {

        switch ($this->getType()) {

            case self::N_M:
                $this->updateModelNM($container);
                break;

            case self::ONE_N:
            default:
                $this->updateModel1N($container);
                break;
        }

        foreach ($this->collectorFilterList as $filter) {
            $filter->updateModel($container);
        }
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    protected function updateModel1N(DataModelContainer $container)
    {
        $this->foreignClassEntity = $container->getEntityByClassname($this->collectorSource->getForeignClass());

        if ($this->foreignClassEntity) {
            $this->reference = $this->foreignClassEntity->getReferenceByName($this->getReferenceName());

            foreach ($this->collectorSource->getCollectorFilterSourceList() as $filterSource) {
                $this->collectorFilterList[] = new CollectorFilter($this->foreignClassEntity, $this, $filterSource);
            }
        }

        if ($this->reference) {
            $this->reference->addCollectorFilter($this->collectorFilterList);
        }
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    protected function updateModelNM(DataModelContainer $container)
    {
        $this->foreignClassEntity = $container->getEntityByClassname($this->collectorSource->getForeignClass());

        $this->mappingClassEntity = $container->getEntityByClassname($this->getMappingClass());

        $this->reference = $this->mappingClassEntity->getReferenceByName($this->getReferenceName());

        //
        $this->nmMapping = new NMMapping();
        $this->nmMapping->foreignEntity = $this->entity;
        $this->nmMapping->mappingEntity = $this->mappingClassEntity;
        $this->nmMapping->entity = $this->foreignClassEntity;
        $this->nmMapping->collector = $this;

        // inform other class that a nm mapping is needed
        $this->foreignClassEntity->addNMMapping($this->nmMapping);
    }

    /**
     * @return string
     */
    public function getNMThisMethodName()
    {
        if ($this->mappingClassEntity === null) {
            return null;
        }
        $reference = $this->mappingClassEntity->getReferenceByName($this->getReferenceName());
        return $reference->getMethodName();
    }

    /**
     * @return string
     */
    public function getNMForeignMethodName()
    {
        if ($this->mappingClassEntity === null) {
            return null;
        }
        foreach ($this->mappingClassEntity->getReferenceGeneratorSourceList() as $reference) {
            if ($reference->getForeignTable() === $this->foreignClassEntity->getTable()) {
                return $reference->getMethodName();
            }
        }
    }

    /**
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(ValidationLogger $logger)
    {
        foreach ($this->collectorFilterList as $filter) {
            $filter->validate($logger);
        }

        if (!$this->getName()) {
            $logger->error("Collector without name found", self::VALIDATION_ERROR_INVALID_NAME);
        }

        switch ($this->getType()) {
            case self::N_M:
                $this->validateNM($logger);
                break;

            case self::ONE_N:
            default:
                $this->validate1N($logger);
                break;
        }

    }

    /**
     * @param ValidationLogger $logger
     */
    protected function validate1N(ValidationLogger $logger)
    {
        $foreignClassName = $this->collectorSource->getForeignClass();

        $logger->errorIfNull($this->foreignClassEntity, "Collector '" . $this->getName() . "' refers to unknown entity " . $foreignClassName, self::VALIDATION_ERROR_INVALID_ENTITY_REFERENCED);

        $logger->errorIfNull($this->reference, "Collector '" . $this->getName() . "' refers to unknown reference " . $this->getReferenceName(), self::VALIDATION_ERROR_INVALID_REFERENCE);
    }

    /**
     * @param ValidationLogger $logger
     */
    protected function validateNM(ValidationLogger $logger)
    {
        $logger->errorIfNull($this->foreignClassEntity, "Collector '" . $this->getName() . "' refers to unknown entity ", self::VALIDATION_ERROR_INVALID_ENTITY_REFERENCED);

        $logger->errorIfNull($this->mappingClassEntity, "Collector '" . $this->getName() . "' refers to unknown mapping entity " . $this->getMappingClass(), self::VALIDATION_ERROR_INVALID_MAPPING_CLASS);

        if ($this->mappingClassEntity !== null) {
            $reference = $this->mappingClassEntity->getReferenceByName($this->getReferenceName());
            $logger->errorIfNull($reference, "Collector '" . $this->getName() . "' mapping entity does not refer this mapping class " . $this->getMappingClass() . " reference name " . $this->getReferenceName(), self::VALIDATION_ERROR_INVALID_MAPPING_CLASS);

        }
    }

    /**
     * @return EntityGeneratorSource
     */
    public function getReferencedEntity()
    {
        return $this->foreignClassEntity;
    }

    /**
     * @return string
     */
    public function getForeignClass()
    {
        return $this->foreignClassEntity->getClassName();
    }

    /**
     * @return string
     */
    public function getForeignConstructClass()
    {
        return $this->foreignClassEntity->getConstructorClass();
    }

    /**
     * @return CollectorFilterSource[]
     */
    public function getCollectorFilterSourceList()
    {
        return $this->collectorFilterList;
    }

    /**
     * @return string[]
     */
    public function getReferencedFullyQualifiedClassName()
    {
        if ($this->foreignClassEntity === null) {
            return [];
        }
        $ems = $this->foreignClassEntity->getEntityManagerSource();


        $usedClassList = [
            $this->foreignClassEntity->getFullyQualifiedConstructClassName(),
            $ems->getFullyQualifiedClassName(),
            $ems->getConstructFactoryFqn()
        ];
        if ($this->getType() === self::N_M) {
            $usedClassList[] = $this->mappingClassEntity->getFullyQualifiedClassName();
        };


        return $usedClassList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->collectorSource->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->collectorSource->getType();
    }

    /**
     * @return string
     */
    public function getMappingClass()
    {
        return $this->collectorSource->getMappingClass();
    }

    /**
     * @return null|string
     */
    public function getNMMappingMethodName()
    {
        if ($this->nmMapping === null) {
            return null;
        }
        return $this->nmMapping->getPHPMethodName();
    }

    /**
     * @return EntitySource
     */
    public function getMappingClassEntity()
    {
        return $this->mappingClassEntity;
    }

    /**
     * @return ReferenceSource
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getReferenceName()
    {
        return $this->collectorSource->getReferenceName();
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->collectorSource->getName());
    }

    /**
     * @return string
     */
    public function getReferenceMethodName()
    {
        return ucfirst($this->getReferenceName());
    }

}