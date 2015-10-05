<?php


namespace siestaphp\datamodel\collector;

/**
 * Interface CollectorSource
 *
 * @package siestaphp\datamodel
 */
interface CollectorSource {

    public function getName();

    public function getType();

    public function getForeignClass();

    public function getReferenceName();

}