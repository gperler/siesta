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
    public function getTargetPath();
}