<?php

namespace siestaphp\datamodel\index;

/**
 * Interface IndexPartSource
 * @package siestaphp\datamodel\index
 */
interface IndexPartSource
{

    /**
     * @return string
     */
    public function getColumnName();

    /**
     * @return string
     */
    public function getSortOrder();

    /**
     * @return int
     */
    public function getLength();

}