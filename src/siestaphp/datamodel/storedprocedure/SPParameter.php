<?php


namespace siestaphp\datamodel\storedprocedure;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\generator\GeneratorLog;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class SPParameter
 * @package siestaphp\datamodel
 */
class SPParameter implements Processable, SPParameterSource
{

    /**
     * @var SPParameterSource
     */
    protected $spParameterSource;

    /**
     * @param SPParameterSource $source
     */
    public function setSource(SPParameterSource $source)
    {
        $this->spParameterSource = $source;

    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {

    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)

    {
        $log->errorIfAttributeNotSet($this->getName(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_NAME, XMLStoredProcedure::ELEMENT_PARAMETER);
        $log->errorIfAttributeNotSet($this->getStoredProcedureName(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_SP_NAME, XMLStoredProcedure::ELEMENT_PARAMETER);
        $log->errorIfAttributeNotSet($this->getPHPType(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_TYPE, XMLStoredProcedure::ELEMENT_PARAMETER);
        $log->errorIfAttributeNotSet($this->getDatabaseType(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_DATABASE_TYPE, XMLStoredProcedure::ELEMENT_PARAMETER);

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->spParameterSource->getName();
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return $this->spParameterSource->getPHPType();
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->spParameterSource->getDatabaseType();
    }

    /**
     * @return string
     */
    public function getStoredProcedureName()
    {
        return $this->spParameterSource->getStoredProcedureName();
    }

}