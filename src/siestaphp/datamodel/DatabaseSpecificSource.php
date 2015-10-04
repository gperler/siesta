<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 28.06.15
 * Time: 11:24
 */

namespace siestaphp\datamodel;


/**
 * Interface DatabaseSpecificSource
 * @package siestaphp\datamodel
 */
interface DatabaseSpecificSource {

    /**
     * @return string
     */
    public function getDatabase();

    /**
     * @param $name
     * @return string
     */
    public function getAttribute($name);

    /**
     * @param $name
     * @return string
     */
    public function getAttributeAsBool($name);

}