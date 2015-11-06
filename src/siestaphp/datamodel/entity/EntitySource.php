<?php

namespace siestaphp\datamodel\entity;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\manager\EntityManagerSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;

/**
 * Interface EntitySource
 * @package siestaphp\datamodel
 */
interface EntitySource
{
    /**
     * @return AttributeSource[]
     */
    public function getAttributeSourceList();

    /**
     * @return ReferenceSource[]
     */
    public function getReferenceSourceList();

    /**
     * @return StoredProcedureSource[]
     */
    public function getStoredProcedureSourceList();

    /**
     * @return CollectorSource[]
     */
    public function getCollectorSourceList();

    /**
     * @return IndexSource[]
     */
    public function getIndexSourceList();

    /**
     * @return EntityManagerSource
     */
    public function getEntityManagerSource();

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
    public function getConstructFactory();

    /**
     * @return string
     */
    public function getConstructFactoryFqn();

    /**
     * @return string
     */
    public function getTable();

    /**
     * @return boolean
     */
    public function isDelimit();

    /**
     * @return string
     */
    public function getTargetPath();

    /**
     * @param string $database
     *
     * @return DatabaseSpecificSource
     */
    public function getDatabaseSpecific($database);
}