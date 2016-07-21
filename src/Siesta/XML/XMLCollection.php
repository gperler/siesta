<?php
declare(strict_types = 1);

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
    protected $foreignReferenceName;

    /**
     * XMLCollection constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setForeignReferenceName($xmlAccess->getAttribute(self::FOREIGN_REFERENCE_NAME));
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
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
    public function getForeignReferenceName()
    {
        return $this->foreignReferenceName;
    }

    /**
     * @param string $foreignReferenceName
     */
    public function setForeignReferenceName($foreignReferenceName)
    {
        $this->foreignReferenceName = $foreignReferenceName;
    }

}