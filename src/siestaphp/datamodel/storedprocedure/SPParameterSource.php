<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 01.07.15
 * Time: 00:31
 */

namespace siestaphp\datamodel\storedprocedure;

/**
 * Interface SPParameterSource
 * @package siestaphp\datamodel
 */
interface SPParameterSource
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getStoredProcedureName();

    /**
     * @return string
     */
    public function getPHPType();

    /**
     * @return string
     */
    public function getDatabaseType();
}