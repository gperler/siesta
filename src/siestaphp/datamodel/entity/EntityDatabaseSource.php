<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 29.06.15
 * Time: 19:52
 */

namespace siestaphp\datamodel\entity;

use siestaphp\datamodel\attribute\AttributeDatabaseSource;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\index\IndexDatabaseSource;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\datamodel\reference\ReferenceSource;

/**
 * Interface EntityDatabaseSource
 * @package siestaphp\datamodel
 */
interface EntityDatabaseSource extends EntitySource {

    /**
     * @param $database
     * @return DatabaseSpecificSource
     */
    public function getDatabaseSpecific($database);

    /**
     * @return AttributeDatabaseSource[]
     */
    public function getAttributeDatabaseSourceList();

    /**
     * @return ReferenceDatabaseSource[]
     */
    public function getReferenceDatabaseSourceList();

    /**
     * @return IndexDatabaseSource[]
     */
    public function getIndexDatabaseSourceList();

    /**
     * @return DatabaseColumn[]
     */
    public function getPrimaryKeyColumns();

    /**
     * @return bool
     */
    public function hasPrimaryKey();

}