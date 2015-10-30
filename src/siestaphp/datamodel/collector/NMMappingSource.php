<?php

namespace siestaphp\datamodel\collector;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface NMMappingSource
 * @package siestaphp\datamodel\collector
 */
interface NMMappingSource
{

    /**
     * @return string
     */
    public function getPHPMethodName();

    /**
     * @return DatabaseColumn[]
     */
    public function getForeignPrimaryKeyColumnList();

    /**
     * @return string
     */
    public function getStoredProcedureName();

}