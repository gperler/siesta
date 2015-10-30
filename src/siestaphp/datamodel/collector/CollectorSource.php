<?php

namespace siestaphp\datamodel\collector;

/**
 * Interface CollectorSource
 * @package siestaphp\datamodel
 */
interface CollectorSource
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getForeignClass();

    /**
     * @return string
     */
    public function getReferenceName();

    /**
     * @return string
     */
    public function getMappingClass();

}