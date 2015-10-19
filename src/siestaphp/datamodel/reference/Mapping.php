<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 11.10.15
 * Time: 11:46
 */

namespace siestaphp\datamodel\reference;

/**
 * Class Mapping
 * @package siestaphp\datamodel\reference
 */
class Mapping implements MappingSource
{
    /**
     * @return string
     */
    public function getName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getForeignName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return null;
    }

}