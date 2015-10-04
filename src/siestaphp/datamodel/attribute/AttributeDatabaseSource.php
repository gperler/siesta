<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 30.06.15
 * Time: 13:30
 */

namespace siestaphp\datamodel\attribute;

/**
 * Interface AttributeDatabaseSource
 * @package siestaphp\datamodel
 */
interface AttributeDatabaseSource extends AttributeSource
{

    /**
     * @return string
     */
    public function getSQLParameterName();

}