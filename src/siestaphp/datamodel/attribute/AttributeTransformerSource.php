<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 29.06.15
 * Time: 11:49
 */

namespace siestaphp\datamodel\attribute;


/**
 * Class AttributeTransformerSource
 * @package siestaphp\datamodel
 */
interface AttributeTransformerSource extends AttributeSource {

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return int
     */
    public function getLength();
}