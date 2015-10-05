<?php


namespace siestaphp\datamodel\attribute;

/**
 * Class AttributeTransformerSource
 * @package siestaphp\datamodel
 */
interface AttributeTransformerSource extends AttributeSource
{

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return int
     */
    public function getLength();
}