<?php

namespace siestaphp\datamodel\storedprocedure;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\generator\GeneratorLog;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class StoredProcedure
 * @package siestaphp\datamodel
 */
class StoredProcedure implements Processable, StoredProcedureSource
{
    const VALIDATION_ERROR_INVALID_NAME = 600;

    const VALIDATION_ERROR_INVALID_RESULT_TYPE = 601;

    private static $ALLOWED_RESULT_TYPES = array("single", "list", "resultset");

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var StoredProcedureSource
     */
    protected $storedProcedureSource;

    /**
     * @var SPParameter[]
     */
    protected $parameterList;

    /**
     *
     */
    public function __construct()
    {
        $this->parameterList = array();
    }

    /**
     * @param StoredProcedureSource $source
     * @param Entity $entity
     */
    public function setSource(StoredProcedureSource $source, Entity $entity)
    {
        $this->entity = $entity;
        $this->storedProcedureSource = $source;
        $this->storeSPParameter();

    }

    /**
     *
     */
    protected function storeSPParameter()
    {
        foreach ($this->storedProcedureSource->getParameterList() as $spParameterSource) {
            $spParameter = new SPParameter();
            $spParameter->setSource($spParameterSource);
            $this->parameterList[] = $spParameter;
        }
    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        foreach ($this->parameterList as $parameter) {
            $parameter->updateModel($container);
        }
    }



    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {
        $log->errorIfAttributeNotSet($this->getName(), XMLStoredProcedure::ATTRIBUTE_NAME, XMLStoredProcedure::ATTRIBUTE_NAME, self::VALIDATION_ERROR_INVALID_NAME);
        $log->errorIfNotInList($this->getResultType(), self::$ALLOWED_RESULT_TYPES, XMLStoredProcedure::ATTRIBUTE_RESULT_TYPE, XMLStoredProcedure::ELEMENT_STORED_PROCEDURE, self::VALIDATION_ERROR_INVALID_RESULT_TYPE);

        foreach ($this->parameterList as $parameter) {
            $parameter->validate($log);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->storedProcedureSource->getName();
    }

    /**
     * @return bool
     */
    public function modifies()
    {
        return $this->storedProcedureSource->modifies();
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->entity->getTable() . "_" . $this->storedProcedureSource->getName();
    }

    /**
     * @return SPParameterSource[]
     */
    public function getParameterList()
    {
        return $this->parameterList;
    }

    /**
     * @param string $databaseName
     *
     * @return string
     */
    public function getSql($databaseName = null)
    {
        return $this->storedProcedureSource->getSql($databaseName);
    }

    /**
     * @return string
     */
    public function getResultType()
    {
        return strtolower($this->storedProcedureSource->getResultType());
    }

}