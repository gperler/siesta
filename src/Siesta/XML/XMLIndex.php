<?php
declare(strict_types=1);

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
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var bool
     */
    protected bool $isUnique;

    /**
     * @var string|null
     */
    protected ?string $indexType;

    /**
     * @var XMLIndexPart[]
     */
    protected array $indexPartList;

    /**
     * XMLIndex constructor.
     */
    public function __construct()
    {
        $this->name = null;
        $this->isUnique = false;
        $this->indexType = null;
        $this->indexPartList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
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
    public function toXML(XMLWrite $parent): void
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
    public function fromIndexMetaData(IndexMetaData $indexMetaData): void
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function getIsUnique(): bool
    {
        return $this->isUnique;
    }

    /**
     * @param bool $isUnique
     */
    public function setIsUnique(bool $isUnique): void
    {
        $this->isUnique = $isUnique;
    }

    /**
     * @return string|null
     */
    public function getIndexType(): ?string
    {
        return $this->indexType;
    }

    /**
     * @param string|null $indexType
     */
    public function setIndexType(?string $indexType): void
    {
        $this->indexType = $indexType;
    }

    /**
     * @return XMLIndexPart[]
     */
    public function getIndexPartList(): array
    {
        return $this->indexPartList;
    }

}