<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Database\MetaData\IndexPartMetaData;

/**
 * @author Gregor Müller
 */
class XMLIndexPart
{

    const ELEMENT_INDEX_PART_NAME = "indexPart";

    const COLUMN_NAME = "columnName";

    const SORT_ORDER = "sortOrder";

    const LENGTH = "length";

    /**
     * @var string
     */
    protected $columnName;

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
        $this->setColumnName($xmlAccess->getAttribute(self::COLUMN_NAME));
        $this->setLength($xmlAccess->getAttributeAsInt(self::LENGTH));
        $this->setSortOrder($xmlAccess->getAttribute(self::SORT_ORDER));
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_INDEX_PART_NAME);
        $xmlWrite->setAttribute(self::COLUMN_NAME, $this->getColumnName());
        $xmlWrite->setIntAttribute(self::LENGTH, $this->getLength());
        $xmlWrite->setAttribute(self::SORT_ORDER, $this->getSortOrder());

    }

    /**
     * @param IndexPartMetaData $indexPartMetaData
     */
    public function fromIndexPartMetaData(IndexPartMetaData $indexPartMetaData)
    {
        $this->setColumnName($indexPartMetaData->getColumnName());
        $this->setLength($indexPartMetaData->getLength());
        $this->setSortOrder($indexPartMetaData->getSortOrder());
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
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