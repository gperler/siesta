<?php

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