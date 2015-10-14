<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\reference\MappingSource;
use siestaphp\naming\XMLMapping;

/**
 * Class XMLMappingReader
 * @package siestaphp\xmlreader
 */
class XMLMappingReader extends XMLAccess implements MappingSource
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLMapping::ATTRIBUTE_NAME);
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->getAttribute(XMLMapping::ATTRIBUTE_DATABASE_NAME);
    }

    /**
     * @return string
     */
    public function getForeignName()
    {
        return $this->getAttribute(XMLMapping::ATTRIBUTE_FOREIGN_NAME);
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return null;
    }

}