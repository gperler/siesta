<?php


namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface IndexPartDatabaseSource
 * @package siestaphp\datamodel\index
 */
interface IndexPartDatabaseSource extends IndexPartSource {

    /**
     * @return DatabaseColumn[]
     */
    public function getIndexColumnList();
}