<?php
declare(strict_types = 1);

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
    protected $mappingTable;

    /**
     * XMLCollectionMany constructor.
     */
    public function __construct()
    {
    }

    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
        $this->setMappingTable($xmlAccess->getAttribute(self::MAPPING_TABLE));
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
     * @return string
     */
    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    /**
     * @param string $foreignTable
     */
    public function setForeignTable($foreignTable)
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string
     */
    public function getMappingTable()
    {
        return $this->mappingTable;
    }

    /**
     * @param string $mappingTable
     */
    public function setMappingTable($mappingTable)
    {
        $this->mappingTable = $mappingTable;
    }

}