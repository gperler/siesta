<?php

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