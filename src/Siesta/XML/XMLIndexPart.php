<?php
declare(strict_types=1);

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
     * @var string|null
     */
    protected ?string $attributeName;

    /**
     * @var string|null
     */
    protected ?string $sortOrder;

    /**
     * @var int|null
     */
    protected ?int $length;

    /**
     *
     */
    public function __construct()
    {
        $this->attributeName = null;
        $this->sortOrder = null;
        $this->length = null;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setAttributeName($xmlAccess->getAttribute(self::ATTRIBUTE_NAME));
        $this->setLength($xmlAccess->getAttributeAsInt(self::LENGTH));
        $this->setSortOrder($xmlAccess->getAttribute(self::SORT_ORDER));
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent): void
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_INDEX_PART_NAME);
        $xmlWrite->setAttribute(self::ATTRIBUTE_NAME, $this->getAttributeName());
        $xmlWrite->setIntAttribute(self::LENGTH, $this->getLength());
        $xmlWrite->setAttribute(self::SORT_ORDER, $this->getSortOrder());
    }

    /**
     * @param IndexPartMetaData $indexPartMetaData
     */
    public function fromIndexPartMetaData(IndexPartMetaData $indexPartMetaData): void
    {
        $namingStrategy = NamingStrategyRegistry::getAttributeNamingStrategy();
        $attributeName = $namingStrategy->transform($indexPartMetaData->getColumnName());
        $this->setAttributeName($attributeName);
        $this->setLength($indexPartMetaData->getLength());
        $this->setSortOrder($indexPartMetaData->getSortOrder());
    }

    /**
     * @return string|null
     */
    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    /**
     * @param string|null $attributeName
     */
    public function setAttributeName(?string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    /**
     * @return string|null
     */
    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    /**
     * @param string|null $sortOrder
     */
    public function setSortOrder(?string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|null $length
     */
    public function setLength(?int $length): void
    {
        $this->length = $length;
    }

}