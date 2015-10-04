<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 22.08.15
 * Time: 16:08
 */

namespace siestaphp\datamodel\reference;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface ReferencedColumnSource
 * @package siestaphp\datamodel
 */
interface ReferencedColumnSource extends DatabaseColumn {


    /**
     * @return string
     */
    public function getReferencedColumnName();


    /**
     * @return string
     */
    public function getMethodName();

}