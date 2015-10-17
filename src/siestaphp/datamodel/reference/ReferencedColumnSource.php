<?php

namespace siestaphp\datamodel\reference;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface ReferencedColumnSource
 * @package siestaphp\datamodel
 */
interface ReferencedColumnSource extends DatabaseColumn
{

    /**
     * @return string
     */
    public function getReferencedColumnMethodName();

    /**
     * @return string
     */
    public function getReferencedDatabaseName();

    /**
     * @return string
     */
    public function getMethodName();

}