<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 30.06.15
 * Time: 13:32
 */

namespace siestaphp\datamodel\reference;


/**
 * Interface ReferenceDatabaseSource
 * @package siestaphp\datamodel
 */
interface ReferenceDatabaseSource extends ReferenceSource
{

    /**
     * @return string
     */
    public function getReferencedTableName();


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

}