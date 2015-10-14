<?php


namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface IndexPartGeneratorSource
 * @package siestaphp\datamodel\index
 */
interface IndexPartGeneratorSource extends IndexPartSource {

    /**
     * @return DatabaseColumn[]
     */
    public function getIndexColumnList();
}