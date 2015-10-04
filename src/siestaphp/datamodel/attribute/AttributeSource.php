<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.06.15
 * Time: 20:25
 */

namespace siestaphp\datamodel\attribute;

/**
 * Interface AttributeSource
 * @package siestaphp\datamodel
 */
interface AttributeSource
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getPHPType();

    /**
     * @return string
     */
    public function getAutoValue();

    /**
     * @return string
     */
    public function getDatabaseName();

    /**
     * @return string
     */
    public function getDatabaseType();

    /**
     * @return string
     */
    public function getDefaultValue();

    /**
     * @return bool
     */
    public function isPrimaryKey();

    /**
     * @return bool
     */
    public function isRequired();
}