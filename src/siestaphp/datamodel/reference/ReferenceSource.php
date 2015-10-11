<?php

namespace siestaphp\datamodel\reference;

use siestaphp\generator\GeneratorLog;
use siestaphp\runtime\ServiceLocator;

/**
 * Class Reference
 * @package siestaphp\datamodel
 */
interface ReferenceSource
{

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return string
     */
    public function getRelationName();

    /**
     * @return mixed
     */
    public function getForeignClass();

    /**
     * @return mixed
     */
    public function isRequired();

    /**
     * @return string
     */
    public function getOnDelete();

    /**
     * @return string
     */
    public function getOnUpdate();

    /**
     * @return bool
     */
    public function isPrimaryKey();

    /**
     * @return string
     */
    public function getConstraintName();

    /**
     * @return MappingSource[]
     */
    public function getMappingSourceList();

}