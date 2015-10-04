<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 28.09.15
 * Time: 21:55
 */

namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface IndexDatabaseSource
 * @package siestaphp\datamodel\index
 */
interface IndexDatabaseSource extends IndexSource {

    /**
     * @return IndexPartDatabaseSource[]
     */
    public function getIndexDatabaseSourceList();




}