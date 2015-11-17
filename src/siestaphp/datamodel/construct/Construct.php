<?php

namespace siestaphp\datamodel\construct;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\XMLConstruct;

/**
 * Class Construct
 * @package siestaphp\datamodel\construct
 */
class Construct implements ConstructSource, Processable
{

    const VALIDATION_ERROR_INVALID_CLASSNAME = 900;

    const VALIDATION_ERROR_INVALID_NAMESPACE = 901;

    const VALIDATION_ERROR_INVALID_CF_NAMESPACE = 902;

    /**
     * @var EntitySource
     */
    protected $entity;

    /**
     * @var ConstructSource
     */
    protected $constructSource;

    /**
     * @param EntitySource $entity
     * @param ConstructSource $constructSource
     */
    public function __construct(EntitySource $entity, $constructSource)
    {
        $this->entity = $entity;
        $this->constructSource = $constructSource;
    }

    /**
     * @return string
     */
    public function getConstructorClass()
    {
        if ($this->constructSource !== null) {
            return $this->constructSource->getConstructorClass();
        }
        return $this->entity->getClassName();
    }

    /**
     * @return string
     */
    public function getConstructorNamespace()
    {
        if ($this->constructSource !== null) {
            return $this->constructSource->getConstructorNamespace();
        }
        return $this->entity->getClassNamespace();
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName()
    {
        if ($this->constructSource !== null) {
            return $this->constructSource->getFullyQualifiedClassName();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getConstructFactory()
    {
        if ($this->constructSource !== null) {
            return $this->constructSource->getConstructFactory();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getConstructFactoryFqn()
    {
        if ($this->constructSource !== null) {
            return $this->constructSource->getConstructFactoryFqn();
        }
        return null;
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
        if ($this->constructSource === null) {
            return;
        }

        $entityManagerClass = $this->getConstructorClass();
        $logger->errorIfInvalidClassName($entityManagerClass, XMLConstruct::ATTRIBUTE_CLASS_NAME, XMLConstruct::ELEMENT_CONSTRUCT_NAME, self::VALIDATION_ERROR_INVALID_CLASSNAME);

        $entityManagerNamespace = $this->getConstructorNamespace();
        $logger->errorIfInvalidNamespace($entityManagerNamespace, XMLConstruct::ATTRIBUTE_CLASS_NAMESPACE, XMLConstruct::ELEMENT_CONSTRUCT_NAME, self::VALIDATION_ERROR_INVALID_NAMESPACE);

        $constructFactoryNS = $this->getConstructFactoryFqn();
        $logger->errorIfInvalidNamespace($constructFactoryNS, XMLConstruct::ATTRIBUTE_CONSTRUCT_FACTORY_FQN, XMLConstruct::ELEMENT_CONSTRUCT_NAME, self::VALIDATION_ERROR_INVALID_CF_NAMESPACE);

    }

}