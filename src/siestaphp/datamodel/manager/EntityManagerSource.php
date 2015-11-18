<?php

namespace siestaphp\datamodel\manager;

/**
 * Interface ManagerSource
 * @package siestaphp\datamodel\manager
 */
interface EntityManagerSource
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getClassNamespace();

    /**
     * @return string
     */
    public function getConstructorClass();

    /**
     * @return string
     */
    public function getConstructorNamespace();

    /**
     * @return string
     */
    public function getTargetPath();

    /**
     * @return string
     */
    public function getFullyQualifiedClassName();

    /**
     * @return string
     */
    public function getConstructFactory();

    /**
     * @return string
     */
    public function getConstructFactoryFqn();


}