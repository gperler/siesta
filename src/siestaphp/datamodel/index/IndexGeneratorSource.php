<?php

namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface IndexGeneratorSource
 * @package siestaphp\datamodel\index
 */
interface IndexGeneratorSource extends IndexSource {

    /**
     * @return IndexPartGeneratorSource[]
     */
    public function getIndexPartGeneratorSourceList();

}