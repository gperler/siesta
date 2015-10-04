<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.09.15
 * Time: 11:40
 */

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