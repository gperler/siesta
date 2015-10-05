<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\naming\XMLIndexPart;

/**
 * Class XMLIndexPartReader
 * @package siestaphp\xmlreader
 */
class XMLIndexPartReader extends XMLAccess implements IndexPartSource
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLIndexPart::ATTRIBUTE_NAME);
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getAttribute(XMLIndexPart::ATTRIBUTE_SORT_ORDER);
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->getAttribute(XMLIndexPart::ATTRIBUTE_LENGTH);
    }

}