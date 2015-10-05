<?php


namespace siestaphp\datamodel\attribute;

/**
 * Interface AttributeDatabaseSource
 * @package siestaphp\datamodel
 */
interface AttributeDatabaseSource extends AttributeSource
{

    /**
     * @return string
     */
    public function getSQLParameterName();

}