<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 29.06.15
 * Time: 14:56
 */

namespace siestaphp\datamodel\reference;

/**
 * Interface ReferenceTransformerSource
 * @package siestaphp\datamodel
 */
interface ReferenceTransformerSource extends ReferenceSource
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
    public function getReferenceColumnList();


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

}