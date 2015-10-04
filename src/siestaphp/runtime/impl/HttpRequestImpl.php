<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 23.06.15
 * Time: 20:41
 */

namespace siestaphp\runtime\impl;

use siestaphp\runtime\DateTime;
use siestaphp\runtime\HttpRequest;

/**
 * Class HttpRequestImpl
 * @package siestaphp\runtime
 */
class HttpRequestImpl implements HttpRequest
{

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function getBooleanValue($key)
    {

    }

    /**
     * @param string $key
     *
     * @return integer
     */
    public function getIntegerValue($key)
    {

    }

    /**
     * @param string $key
     *
     * @return float
     */
    public function getFloatValue($key)
    {

    }

    /**
     * @param string $key
     * @param int $maxLength
     *
     * @return string
     */
    public function getStringValue($key, $maxLength = 0)
    {

    }

    /**
     * @param string $key
     *
     * @return DateTime
     */
    public function getDateTime($key)
    {

    }

}