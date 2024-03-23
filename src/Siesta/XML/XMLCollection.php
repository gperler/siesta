<?php
declare(strict_types=1);

namespace Siesta\XML;

/**
 * @author Gregor Müller
 */
class XMLCollection
{

    const ELEMENT_COLLECTION_NAME = "collection";

    const NAME = "name";

    const FOREIGN_TABLE = "foreignTable";

    const FOREIGN_REFERENCE_NAME = "foreignReferenceName";

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
    protected ?string $foreignReferenceName;

    /**
     * XMLCollection constructor.
     */
    public function __construct()
    {
        $this->name = null;
        $this->foreignTable = null;
        $this->foreignReferenceName = null;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setForeignReferenceName($xmlAccess->getAttribute(self::FOREIGN_REFERENCE_NAME));
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
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
    public function getForeignReferenceName(): ?string
    {
        return $this->foreignReferenceName;
    }

    /**
     * @param string|null $foreignReferenceName
     */
    public function setForeignReferenceName(?string $foreignReferenceName): void
    {
        $this->foreignReferenceName = $foreignReferenceName;
    }

}