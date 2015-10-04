<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 26.06.15
 * Time: 19:13
 */

namespace siestaphp\runtime;


/**
 * Interface UUIDGenerator
 * @package siestaphp\runtime
 */
interface UUIDGenerator {

    /**
     * @return string
     */
    public function uuid();
}