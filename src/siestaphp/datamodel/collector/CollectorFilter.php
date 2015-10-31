<?php

namespace siestaphp\datamodel\collector;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\datamodel\storedprocedure\SPParameter;
use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\StoredProcedureNaming;
use siestaphp\naming\XMLCollectorFilter;

/**
 * Class CollectorFilter
 * @package siestaphp\datamodel\reference
 */
class CollectorFilter implements CollectorFilterSource, Processable
{

    const VALIDATION_ERROR_INVALID_NAME = 800;

    const VALIDATION_ERROR_INVALID_FILTER = 801;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Collector
     */
    protected $collector;

    /**
     * @var CollectorFilterSource
     */
    protected $referenceFilterSource;

    /**
     * @var SPParameter[]
     */
    protected $spParameterList;

    /**
     * @param Entity $entity
     * @param Collector $collector
     * @param CollectorFilterSource $source
     */
    public function __construct(Entity $entity, Collector $collector, CollectorFilterSource $source)
    {
        $this->entity = $entity;
        $this->collector = $collector;
        $this->referenceFilterSource = $source;

        foreach ($this->referenceFilterSource->getSPParameterList() as $parameter) {
            $spParameter = new SPParameter();
            $spParameter->setSource($parameter);
            $this->spParameterList[] = $spParameter;
        }

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->referenceFilterSource->getName();
    }

    /**
     * @return string
     */
    public function getSPName()
    {
        return StoredProcedureNaming::getSPFinByCollectorFilterName($this->entity->getTable(), $this->collector->getName(),$this->getName() );
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->referenceFilterSource->getFilter();
    }

    /**
     * @return SPParameterSource
     */
    public function getSPParameterList()
    {
        return $this->spParameterList;
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container)
    {

    }

    /**
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(ValidationLogger $logger)
    {
        $logger->errorIfAttributeNotSet($this->getName(), XMLCollectorFilter::ATTRIBUTE_FILTER_NAME, XMLCollectorFilter::ELEMENT_FILTER_NAME, self::VALIDATION_ERROR_INVALID_NAME);

        $logger->errorIfAttributeNotSet($this->getFilter(), XMLCollectorFilter::ATTRIBUTE_FILTER_FILTER, XMLCollectorFilter::ELEMENT_FILTER_NAME, self::VALIDATION_ERROR_INVALID_FILTER);

        foreach ($this->spParameterList as $parameter) {
            $parameter->validate($logger);
        }
    }

}