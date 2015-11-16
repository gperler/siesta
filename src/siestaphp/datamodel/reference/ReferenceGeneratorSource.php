<?php

namespace siestaphp\datamodel\reference;

use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\entity\EntityGeneratorSource;

/**
 * Interface ReferenceGeneratorSource
 * @package siestaphp\datamodel
 */
interface ReferenceGeneratorSource extends ReferenceSource
{

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return string
     */
    public function getForeignMethodName();

    /**
     * @return string
     */
    public function getReferencedConstructClass();

    /**
     * @return ReferencedColumnSource[]
     */
    public function getReferencedColumnList();

    /**
     * @return string
     */
    public function getStoredProcedureFinderName();

    /**
     * @return string
     */
    public function getStoredProcedureDeleterName();

    /**
     * @return bool
     */
    public function isReferenceCreatorNeeded();

    /**
     * @return string
     */
    public function getReferencedTableName();

    /**
     * @return CollectorFilterSource[]
     */
    public function getCollectorFilterSourceList();

    /**
     * @return EntityGeneratorSource
     */
    public function getReferencedEntity();

}