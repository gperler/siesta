<?php


namespace siestaphp\datamodel\attribute;

/**
 * Class AttributeGeneratorSource
 * @package siestaphp\datamodel
 */
interface AttributeGeneratorSource extends AttributeSource
{

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return int
     */
    public function getLength();


    /**
     * @return string
     */
    public function getSQLParameterName();
}