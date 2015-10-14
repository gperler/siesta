<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 15.10.15
 * Time: 00:37
 */

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\reference\ReferencedColumnSource;

/**
 * Class ReferencedColumnMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class ReferencedColumnMetaData implements ReferencedColumnSource
{
    /**
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        // TODO: Implement getPHPType() method.
    }

    /**
     * @return mixed
     */
    public function getDatabaseName()
    {
        // TODO: Implement getDatabaseName() method.
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        // TODO: Implement getDatabaseType() method.
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        // TODO: Implement isPrimaryKey() method.
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        // TODO: Implement isRequired() method.
    }

    /**
     * @return string
     */
    public function getSQLParameterName()
    {
        // TODO: Implement getSQLParameterName() method.
    }

    /**
     * @return string
     */
    public function getReferencedColumnName()
    {
        // TODO: Implement getReferencedColumnName() method.
    }

    /**
     * @return string
     */
    public function getReferencedColumnMethodName()
    {
        // TODO: Implement getReferencedColumnMethodName() method.
    }

    /**
     * @return string
     */
    public function getReferencedDatabaseName()
    {
        // TODO: Implement getReferencedDatabaseName() method.
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        // TODO: Implement getMethodName() method.
    }

}