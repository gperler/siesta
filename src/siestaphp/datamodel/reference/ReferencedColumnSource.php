<?php


namespace siestaphp\datamodel\reference;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface ReferencedColumnSource
 * @package siestaphp\datamodel
 */
interface ReferencedColumnSource extends DatabaseColumn {


    /**
     * @return string
     */
    public function getReferencedColumnName();


    /**
     * @return string
     */
    public function getMethodName();

}