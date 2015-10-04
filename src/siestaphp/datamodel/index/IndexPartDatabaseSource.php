<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 29.09.15
 * Time: 15:35
 */

namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;

/**
 * Interface IndexPartDatabaseSource
 * @package siestaphp\datamodel\index
 */
interface IndexPartDatabaseSource extends IndexPartSource {

    /**
     * @return DatabaseColumn[]
     */
    public function getIndexColumnList();
}