<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.09.15
 * Time: 13:26
 */

namespace siestaphp\datamodel\collector;

/**
 * Interface CollectorTransformerSource
 * @package siestaphp\datamodel
 */
interface CollectorTransformerSource extends CollectorSource
{

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return string
     */
    public function getForeignConstructClass();

    /**
     * @return string
     */
    public function getReferenceMethodName();
}