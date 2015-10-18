<?php

namespace siestaphp\datamodel\collector;

use Codeception\Util\Debug;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\datamodel\reference\Reference;
use siestaphp\generator\ValidationLogger;

/**
 * Class Collector
 * @package siestaphp\datamodel
 */
class Collector implements Processable, CollectorSource, CollectorTransformerSource
{
    const VALIDATION_ERROR_INVALID_NAME = 400;

    const VALIDATION_ERROR_INVALID_ENTITY_REFERENCED = 401;

    const VALIDATION_ERROR_INVALID_REFERENCE = 402;

    /**
     * @var CollectorSource
     */
    protected $collectorSource;

    /**
     * @var Entity
     */
    protected $foreignClassEntity;

    /**
     * @var Reference
     */
    protected $reference;

    /**
     * @param CollectorSource $source
     */
    public function setSource(CollectorSource $source)
    {
        $this->collectorSource = $source;
    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        switch ($this->getType()) {
            case "1n":
                $this->updateModel1N($container);
                break;
            case "nm":
                $this->updateModelNM($container);
                break;
        }
    }

    /**
     * @param DataModelContainer $container
     */
    private function updateModel1N(DataModelContainer $container)
    {
        $this->foreignClassEntity = $container->getEntityDetails($this->getForeignClass());

        if ($this->foreignClassEntity) {
            $this->reference = $this->foreignClassEntity->getReferenceByName($this->getReferenceName());
        }
    }

    /**
     * @param DataModelContainer $container
     */
    private function updateModelNM(DataModelContainer $container)
    {

    }

    /**
     * @param ValidationLogger $log
     */
    public function validate(ValidationLogger $log)
    {
        if ($this->getType() !== "1n") {
            return;
        }

        if (!$this->getName()) {
            $log->error("Collector without name found", self::VALIDATION_ERROR_INVALID_NAME);
        }

        if (!$this->foreignClassEntity) {
            $log->error("Collector '" . $this->getName() . "' refers to unknown entity " . $this->getForeignClass(), self::VALIDATION_ERROR_INVALID_ENTITY_REFERENCED);
        }

        if (!$this->reference) {
            $log->error("Collector '" . $this->getName() . "' refers to unknown reference " . $this->getReferenceName(), self::VALIDATION_ERROR_INVALID_REFERENCE);
        }

    }

    /**
     * @return string
     */
    public function getReferencedFullyQualifiedClassName()
    {
        if ($this->foreignClassEntity) {
            return $this->foreignClassEntity->getFullyQualifiedClassName();
        }
        return "";
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
    public function getForeignClass()
    {
        return $this->collectorSource->getForeignClass();
    }

    /**
     * @return string
     */
    public function getMapperClass()
    {
        return $this->collectorSource->getMapperClass();
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
    public function getForeignConstructClass()
    {
        return $this->foreignClassEntity->getConstructorClass();
    }

    /**
     * @return string
     */
    public function getReferenceMethodName()
    {
        return ucfirst($this->getReferenceName());
    }

}