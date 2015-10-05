<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\DatabaseSpecificSource;

/**
 * Class XMLDatabaseSpecific
 * @package siestaphp\datamodel\xml\reader
 */
class XMLDatabaseSpecific extends XMLAccess implements DatabaseSpecificSource
{

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->databaseName;
    }

    /**
     * @param $db
     */
    public function setDatabase($db)
    {
        $this->databaseName = $db;
    }

}