<?php

namespace siestaphp\datamodel\construct;

/**
 * Class ConstructSource
 * @package siestaphp\datamodel\construct
 */
interface ConstructSource
{

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