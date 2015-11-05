<?php

namespace siestaphp\datamodel\reference;

/**
 * Interface MappingSource
 * @package siestaphp\datamodel\reference
 */
interface MappingSource
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDatabaseName();

    /**
     * @return string
     */
    public function getForeignName();

    /**
     * @return string
     */
    public function getDatabaseType();
}