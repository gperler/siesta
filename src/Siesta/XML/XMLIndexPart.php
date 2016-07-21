<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\NamingStrategy\NamingStrategyRegistry;

/**
 * @author Gregor Müller
 */
class XMLIndexPart
{

    const ELEMENT_INDEX_PART_NAME = "indexPart";

    const ATTRIBUTE_NAME = "attributeName";

    const SORT_ORDER = "sortOrder";

    const LENGTH = "length";

    /**
     * @var string
     */
    protected $attributeName;

    /**
     * @var string
     */
    protected $sortOrder;

    /**
     * @var int
     */
    protected $length;

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setAttributeName($xmlAccess->getAttribute(self::ATTRIBUTE_NAME));
        $this->setLength($xmlAccess->getAttributeAsInt(self::LENGTH));
        $this->setSortOrder($xmlAccess->getAttribute(self::SORT_ORDER));
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_INDEX_PART_NAME);
        $xmlWrite->setAttribute(self::ATTRIBUTE_NAME, $this->getAttributeName());
        $xmlWrite->setIntAttribute(self::LENGTH, $this->getLength());
        $xmlWrite->setAttribute(self::SORT_ORDER, $this->getSortOrder());

    }

    /**
     * @param IndexPartMetaData $indexPartMetaData
     */
    public function fromIndexPartMetaData(IndexPartMetaData $indexPartMetaData)
    {
        $namingStratey = NamingStrategyRegistry::getAttributeNamingStrategy();
        $attributeName = $namingStratey->transform($indexPartMetaData->getColumnName());
        $this->setAttributeName($attributeName);
        $this->setLength($indexPartMetaData->getLength());
        $this->setSortOrder($indexPartMetaData->getSortOrder());
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        return $this->attributeName;
    }

    /**
     * @param string $attributeName
     */
    public function setAttributeName($attributeName)
    {
        $this->attributeName = $attributeName;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

}