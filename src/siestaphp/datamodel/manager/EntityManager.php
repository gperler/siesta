<?php

namespace siestaphp\datamodel\manager;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;

/**
 * Class EntityManager
 * @package siestaphp\datamodel\manager
 */
class EntityManager implements EntityManagerSource
{

    /**
     * @var EntitySource
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
        return $this->entity->getClassName() . "Manager";
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
    public function getFullyQualifiedClassName()
    {
        if (empty($this->getClassName())) {
            return null;
        }
        return $this->getClassNamespace() . "\\" . $this->getClassName();
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

}