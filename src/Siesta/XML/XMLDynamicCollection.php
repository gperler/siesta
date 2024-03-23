<?php

declare(strict_types=1);

namespace Siesta\XML;

class XMLDynamicCollection
{

    const ELEMENT_DYNAMIC_COLLECTION_NAME = "dynamic-collection";

    const NAME = "name";

    const FOREIGN_TABLE = "foreignTable";

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $foreignTable;

    /**
     *
     */
    public function __construct()
    {
        $this->name = null;
        $this->foreignTable = null;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
        $this->setName($xmlAccess->getAttribute(self::NAME));
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
     * @return string|null
     */
    public function getForeignTable(): ?string
    {
        return $this->foreignTable;
    }

    /**
     * @param string|null $foreignTable
     */
    public function setForeignTable(?string $foreignTable): void
    {
        $this->foreignTable = $foreignTable;
    }

}