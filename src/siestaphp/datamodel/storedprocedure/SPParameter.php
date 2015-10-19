<?php

namespace siestaphp\datamodel\storedprocedure;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class SPParameter
 * @package siestaphp\datamodel
 */
class SPParameter implements Processable, SPParameterSource
{

    const VALIDATION_ERROR_INVALID_NAME = 700;

    const VALIDATION_ERROR_INVALID_SP_NAME = 701;

    const VALIDATION_ERROR_INVALID_PHP_TYPE = 702;

    const VALIDATION_ERROR_INVALID_DATABASE_TYPE = 703;

    /**
     * @var SPParameterSource
     */
    protected $spParameterSource;

    /**
     * @param SPParameterSource $source
     *
     * @return void
     */
    public function setSource(SPParameterSource $source)
    {
        $this->spParameterSource = $source;

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
     * @param ValidationLogger $log
     *
     * @return void
     */
    public function validate(ValidationLogger $log)

    {
        $log->errorIfAttributeNotSet($this->getName(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_NAME, XMLStoredProcedure::ELEMENT_PARAMETER, self::VALIDATION_ERROR_INVALID_NAME);
        $log->errorIfAttributeNotSet($this->getStoredProcedureName(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_SP_NAME, XMLStoredProcedure::ELEMENT_PARAMETER, self::VALIDATION_ERROR_INVALID_SP_NAME);
        $log->errorIfAttributeNotSet($this->getPHPType(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_TYPE, XMLStoredProcedure::ELEMENT_PARAMETER, self::VALIDATION_ERROR_INVALID_PHP_TYPE);
        $log->errorIfAttributeNotSet($this->getDatabaseType(), XMLStoredProcedure::ATTRIBUTE_PARAMETER_DATABASE_TYPE, XMLStoredProcedure::ELEMENT_PARAMETER, self::VALIDATION_ERROR_INVALID_DATABASE_TYPE);

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