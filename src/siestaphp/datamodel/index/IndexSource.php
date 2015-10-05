<?php

namespace siestaphp\datamodel\index;

/**
 * Interface IndexSource
 * @package siestaphp\datamodel\index
 */
interface IndexSource {

    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isUnique();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return IndexPartSource[]
     */
    public function getIndexPartSourceList();

}

