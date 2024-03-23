<?php
declare(strict_types=1);

namespace Siesta\XML;

/**
 * @author Gregor Müller
 */
class XMLCollectionMany
{

    const ELEMENT_COLLECTION_MANY_NAME = "collectionMany2Many";

    const NAME = "name";

    const FOREIGN_TABLE = "foreignTable";

    const MAPPING_TABLE = "mappingTable";

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $foreignTable;

    /**
     * @var string|null
     */
    protected ?string $mappingTable;

    /**
     * XMLCollectionMany constructor.
     */
    public function __construct()
    {
        $this->name = null;
        $this->foreignTable = null;
        $this->mappingTable = null;
    }

    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
        $this->setMappingTable($xmlAccess->getAttribute(self::MAPPING_TABLE));
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

    /**
     * @return string|null
     */
    public function getMappingTable(): ?string
    {
        return $this->mappingTable;
    }

    /**
     * @param string|null $mappingTable
     */
    public function setMappingTable(?string $mappingTable): void
    {
        $this->mappingTable = $mappingTable;
    }

}