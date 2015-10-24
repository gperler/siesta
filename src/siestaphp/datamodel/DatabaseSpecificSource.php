<?php

namespace siestaphp\datamodel;

/**
 * Interface DatabaseSpecificSource
 * @package siestaphp\datamodel
 */
interface DatabaseSpecificSource
{

    /**
     * @return string
     */
    public function getDatabase();

    /**
     * @param $name
     *
     * @return string
     */
    public function getAttribute($name);

    /**
     * @param $name
     *
     * @return bool
     */
    public function getAttributeAsBool($name);

}