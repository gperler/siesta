<?php

declare(strict_types=1);

namespace Siesta\XML;

class XMLDynamicCollection
{

    const ELEMENT_DYNAMIC_COLLECTION_NAME = "dynamic-collection";

    const NAME = "name";

    const FOREIGN_TABLE = "foreignTable";

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $foreignTable;

    /**
     * @var string
     */
    protected $cardinality;

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
        $this->setName($xmlAccess->getAttribute(self::NAME));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getForeignTable(): string
    {
        return $this->foreignTable;
    }

    /**
     * @param string $foreignTable
     */
    public function setForeignTable(string $foreignTable)
    {
        $this->foreignTable = $foreignTable;
    }

}