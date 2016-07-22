<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Database\MetaData\IndexMetaData;

/**
 * @author Gregor Müller
 */
class XMLIndex
{

    const ELEMENT_INDEX_NAME = "index";

    const INDEX_NAME = "name";

    const UNIQUE = "unique";

    const INDEX_TYPE = "type";

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isUnique;

    /**
     * @var string
     */
    protected $indexType;

    /**
     * @var XMLIndexPart[]
     */
    protected $indexPartList;

    /**
     * XMLIndex constructor.
     */
    public function __construct()
    {
        $this->indexPartList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setName($xmlAccess->getAttribute(self::INDEX_NAME));
        $this->setIndexType($xmlAccess->getAttribute(self::INDEX_TYPE));
        $this->setIsUnique($xmlAccess->getAttributeAsBool(self::UNIQUE));

        foreach ($xmlAccess->getXMLChildElementListByName(XMLIndexPart::ELEMENT_INDEX_PART_NAME) as $xmlIndexPartAccess) {
            $xmlIndexPart = new XMLIndexPart();
            $xmlIndexPart->fromXML($xmlIndexPartAccess);
            $this->indexPartList[] = $xmlIndexPart;
        }
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_INDEX_NAME);
        $xmlWrite->setAttribute(self::INDEX_NAME, $this->getName());
        $xmlWrite->setAttribute(self::INDEX_TYPE, $this->getIndexType());
        $xmlWrite->setBoolAttribute(self::UNIQUE, $this->getIsUnique());
        foreach ($this->getIndexPartList() as $indexPart) {
            $indexPart->toXML($xmlWrite);
        }
    }

    /**
     * @param IndexMetaData $indexMetaData
     */
    public function fromIndexMetaData(IndexMetaData $indexMetaData)
    {
        $this->setName($indexMetaData->getName());
        $this->setIndexType($indexMetaData->getType());
        $this->setIsUnique($indexMetaData->getIsUnique());
        foreach ($indexMetaData->getIndexPartList() as $indexPartMetaData) {
            $xmlIndexPart = new XMLIndexPart();
            $xmlIndexPart->fromIndexPartMetaData($indexPartMetaData);
            $this->indexPartList[] = $xmlIndexPart;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function getIsUnique()
    {
        return $this->isUnique;
    }

    /**
     * @param bool $isUnique
     */
    public function setIsUnique($isUnique)
    {
        $this->isUnique = $isUnique;
    }

    /**
     * @return string
     */
    public function getIndexType()
    {
        return $this->indexType;
    }

    /**
     * @param string $indexType
     */
    public function setIndexType($indexType)
    {
        $this->indexType = $indexType;
    }

    /**
     * @return XMLIndexPart[]
     */
    public function getIndexPartList()
    {
        return $this->indexPartList;
    }

}