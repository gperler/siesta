<?php

namespace siestaphp\datamodel\manager;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\XMLEntityManager;

/**
 * Class EntityManager
 * @package siestaphp\datamodel\manager
 */
class EntityManager implements EntityManagerSource, Processable
{

    const VALIDATION_ERROR_INVALID_CLASSNAME = 1000;

    const VALIDATION_ERROR_INVALID_NAMESPACE = 1001;

    const VALIDATION_ERROR_INVALID_CF_NAMESPACE = 1002;

    /**
     * @var EntityManagerSource
     */
    protected $entityManagerSource;

    /**
     * @var EntityGeneratorSource
     */
    protected $entity;

    /**
     * @param EntitySource $entity
     * @param EntityManagerSource $managerSource
     */
    public function __construct(EntitySource $entity, $managerSource)
    {
        $this->entityManagerSource = $managerSource;
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        if ($this->entityManagerSource !== null) {
            return $this->entityManagerSource->getClassName();
        }
        return $this->entity->getClassName() . "Service";
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
        if ($this->entityManagerSource !== null) {
            return $this->entityManagerSource->getClassNamespace();
        }
        return $this->entity->getClassNamespace();
    }

    /**
     * @return string
     */
    public function getConstructorClass()
    {
        if ($this->entityManagerSource === null) {
            return $this->getClassName();
        }

        if ($this->entityManagerSource->getConstructorClass() === null) {
            return $this->getClassName();
        }

        return $this->entityManagerSource->getConstructorClass();
    }

    /**
     * @return string
     */
    public function getConstructorNamespace()
    {
        if ($this->entityManagerSource === null) {
            return $this->getClassNamespace();
        }

        if ($this->entityManagerSource->getConstructorNamespace() === null) {
            return $this->getClassNamespace();
        }

        return $this->entityManagerSource->getConstructorNamespace();
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName()
    {
        if (empty($this->getClassName())) {
            return null;
        }
        return $this->getConstructorNamespace() . "\\" . $this->getConstructorClass();
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        if ($this->entityManagerSource !== null) {
            return $this->entityManagerSource->getTargetPath();
        }
        return $this->entity->getTargetPath();
    }

    /**
     * @return string
     */
    public function getConstructFactory()
    {
        if ($this->entityManagerSource !== null) {
            return $this->entityManagerSource->getConstructFactory();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getConstructFactoryFqn()
    {
        if ($this->entityManagerSource !== null) {
            return $this->entityManagerSource->getConstructFactoryFqn();
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
        if ($this->entityManagerSource === null) {
            return;
        }

        $entityManagerClass = $this->getClassName();
        $logger->errorIfInvalidClassName($entityManagerClass, XMLEntityManager::ATTRIBUTE_CLASS_NAME, XMLEntityManager::ELEMENT_NAME, self::VALIDATION_ERROR_INVALID_CLASSNAME);

        $entityManagerNamespace = $this->getClassNamespace();
        $logger->errorIfInvalidNamespace($entityManagerNamespace, XMLEntityManager::ATTRIBUTE_CLASS_NAMESPACE, XMLEntityManager::ELEMENT_NAME, self::VALIDATION_ERROR_INVALID_NAMESPACE);

        $constructFactoryNS = $this->getConstructFactoryFqn();
        $logger->errorIfInvalidNamespace($constructFactoryNS, XMLEntityManager::ATTRIBUTE_CONSTRUCT_FACTORY_FQN, XMLEntityManager::ELEMENT_NAME, self::VALIDATION_ERROR_INVALID_CF_NAMESPACE);

    }

}