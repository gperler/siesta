<?php

namespace siestaphp\datamodel\index;

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