<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 23.06.15
 * Time: 15:40
 */

namespace siestaphp\runtime;


/**
 * Interface HttpRequest
 * @package siestaphp\runtime
 */
interface HttpRequest {

    /**
     * @param string $key
     * @return boolean
     */
    public function getBooleanValue($key);


    /**
     * @param string $key
     * @return integer
     */
    public function getIntegerValue($key);


    /**
     * @param string $key
     * @return float
     */
    public function getFloatValue($key);

    /**
     * @param string $key
     * @param $maxLength
     * @return string
     */
    public function getStringValue($key, $maxLength=0);


    /**
     * @param string $key
     * @return DateTime
     */
    public function getDateTime($key);
}