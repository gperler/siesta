<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class sylius_taxon implements ArraySerializable
{

    const TABLE_NAME = "sylius_taxon";

    const COLUMN_ID = "id";

    const COLUMN_TAXONOMY_ID = "taxonomy_id";

    const COLUMN_PARENT_ID = "parent_id";

    const COLUMN_TREE_LEFT = "tree_left";

    const COLUMN_TREE_RIGHT = "tree_right";

    const COLUMN_TREE_LEVEL = "tree_level";

    const COLUMN_DELETED_AT = "deleted_at";

    const COLUMN_PATH = "path";

    /**
     * @var bool
     */
    protected $_existing;

    /**
     * @var array
     */
    protected $_rawJSON;

    /**
     * @var array
     */
    protected $_rawSQLResult;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $taxonomy_id;

    /**
     * @var int
     */
    protected $parent_id;

    /**
     * @var int
     */
    protected $tree_left;

    /**
     * @var int
     */
    protected $tree_right;

    /**
     * @var int
     */
    protected $tree_level;

    /**
     * @var SiestaDateTime
     */
    protected $deleted_at;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var sylius_taxon
     */
    protected $CFD811CA727ACA70;

    /**
     * @var sylius_taxonomy
     */
    protected $CFD811CA9557E6F6;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL sylius_taxon_U(" : "CALL sylius_taxon_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->taxonomy_id) . ',' . Escaper::quoteInt($this->parent_id) . ',' . Escaper::quoteInt($this->tree_left) . ',' . Escaper::quoteInt($this->tree_right) . ',' . Escaper::quoteInt($this->tree_level) . ',' . Escaper::quoteDateTime($this->deleted_at) . ',' . Escaper::quoteString($connection, $this->path) . ');';
    }

    /**
     * @param bool $cascade
     * @param CycleDetector $cycleDetector
     * @param string $connectionName
     * 
     * @return void
     */
    public function save(bool $cascade = false, CycleDetector $cycleDetector = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return;
        }
        if ($cascade && $this->CFD811CA727ACA70 !== null) {
            $this->CFD811CA727ACA70->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->CFD811CA9557E6F6 !== null) {
            $this->CFD811CA9557E6F6->save($cascade, $cycleDetector, $connectionName);
        }
        $call = $this->createSaveStoredProcedureCall($connectionName);
        $connection->execute($call);
        $this->_existing = true;
        if (!$cascade) {
            return;
        }
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return void
     */
    public function fromResultSet(ResultSet $resultSet)
    {
        $this->_existing = true;
        $this->_rawSQLResult = $resultSet->getNext();
        $this->id = $resultSet->getIntegerValue("id");
        $this->taxonomy_id = $resultSet->getIntegerValue("taxonomy_id");
        $this->parent_id = $resultSet->getIntegerValue("parent_id");
        $this->tree_left = $resultSet->getIntegerValue("tree_left");
        $this->tree_right = $resultSet->getIntegerValue("tree_right");
        $this->tree_level = $resultSet->getIntegerValue("tree_level");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
        $this->path = $resultSet->getStringValue("path");
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function getAdditionalColumn(string $key)
    {
        return ArrayUtil::getFromArray($this->_rawSQLResult, $key);
    }

    /**
     * @param string $connectionName
     * 
     * @return void
     */
    public function delete(string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($this->id);
        $connection->execute("CALL sylius_taxon_DB_PK($id)");
        $this->_existing = false;
    }

    /**
     * @param array $data
     * 
     * @return void
     */
    public function fromArray(array $data)
    {
        $this->_rawJSON = $data;
        $arrayAccessor = new ArrayAccessor($data);
        $this->setId($arrayAccessor->getIntegerValue("id"));
        $this->setTaxonomy_id($arrayAccessor->getIntegerValue("taxonomy_id"));
        $this->setParent_id($arrayAccessor->getIntegerValue("parent_id"));
        $this->setTree_left($arrayAccessor->getIntegerValue("tree_left"));
        $this->setTree_right($arrayAccessor->getIntegerValue("tree_right"));
        $this->setTree_level($arrayAccessor->getIntegerValue("tree_level"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->setPath($arrayAccessor->getStringValue("path"));
        $this->_existing = ($this->id !== null);
        $CFD811CA727ACA70Array = $arrayAccessor->getArray("CFD811CA727ACA70");
        if ($CFD811CA727ACA70Array !== null) {
            $CFD811CA727ACA70 = sylius_taxonService::getInstance()->newInstance();
            $CFD811CA727ACA70->fromArray($CFD811CA727ACA70Array);
            $this->setCFD811CA727ACA70($CFD811CA727ACA70);
        }
        $CFD811CA9557E6F6Array = $arrayAccessor->getArray("CFD811CA9557E6F6");
        if ($CFD811CA9557E6F6Array !== null) {
            $CFD811CA9557E6F6 = sylius_taxonomyService::getInstance()->newInstance();
            $CFD811CA9557E6F6->fromArray($CFD811CA9557E6F6Array);
            $this->setCFD811CA9557E6F6($CFD811CA9557E6F6);
        }
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return array|null
     */
    public function toArray(CycleDetector $cycleDetector = null)
    {
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return null;
        }
        $result = [
            "id" => $this->getId(),
            "taxonomy_id" => $this->getTaxonomy_id(),
            "parent_id" => $this->getParent_id(),
            "tree_left" => $this->getTree_left(),
            "tree_right" => $this->getTree_right(),
            "tree_level" => $this->getTree_level(),
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null,
            "path" => $this->getPath()
        ];
        if ($this->CFD811CA727ACA70 !== null) {
            $result["CFD811CA727ACA70"] = $this->CFD811CA727ACA70->toArray($cycleDetector);
        }
        if ($this->CFD811CA9557E6F6 !== null) {
            $result["CFD811CA9557E6F6"] = $this->CFD811CA9557E6F6->toArray($cycleDetector);
        }
        return $result;
    }

    /**
     * @param string $jsonString
     * 
     * @return void
     */
    public function fromJSON(string $jsonString)
    {
        $this->fromArray(json_decode($jsonString, true));
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return string
     */
    public function toJSON(CycleDetector $cycleDetector = null) : string
    {
        return json_encode($this->toArray($cycleDetector));
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return int|null
     */
    public function getTaxonomy_id()
    {
        return $this->taxonomy_id;
    }

    /**
     * @param int $taxonomy_id
     * 
     * @return void
     */
    public function setTaxonomy_id(int $taxonomy_id = null)
    {
        $this->taxonomy_id = $taxonomy_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getParent_id()
    {
        return $this->parent_id;
    }

    /**
     * @param int $parent_id
     * 
     * @return void
     */
    public function setParent_id(int $parent_id = null)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getTree_left()
    {
        return $this->tree_left;
    }

    /**
     * @param int $tree_left
     * 
     * @return void
     */
    public function setTree_left(int $tree_left = null)
    {
        $this->tree_left = $tree_left;
    }

    /**
     * 
     * @return int|null
     */
    public function getTree_right()
    {
        return $this->tree_right;
    }

    /**
     * @param int $tree_right
     * 
     * @return void
     */
    public function setTree_right(int $tree_right = null)
    {
        $this->tree_right = $tree_right;
    }

    /**
     * 
     * @return int|null
     */
    public function getTree_level()
    {
        return $this->tree_level;
    }

    /**
     * @param int $tree_level
     * 
     * @return void
     */
    public function setTree_level(int $tree_level = null)
    {
        $this->tree_level = $tree_level;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getDeleted_at()
    {
        return $this->deleted_at;
    }

    /**
     * @param SiestaDateTime $deleted_at
     * 
     * @return void
     */
    public function setDeleted_at(SiestaDateTime $deleted_at = null)
    {
        $this->deleted_at = $deleted_at;
    }

    /**
     * 
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * 
     * @return void
     */
    public function setPath(string $path = null)
    {
        $this->path = StringUtil::trimToNull($path, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_taxon|null
     */
    public function getCFD811CA727ACA70(bool $forceReload = false)
    {
        if ($this->CFD811CA727ACA70 === null || $forceReload) {
            $this->CFD811CA727ACA70 = sylius_taxonService::getInstance()->getEntityByPrimaryKey($this->parent_id);
        }
        return $this->CFD811CA727ACA70;
    }

    /**
     * @param sylius_taxon $entity
     * 
     * @return void
     */
    public function setCFD811CA727ACA70(sylius_taxon $entity = null)
    {
        $this->CFD811CA727ACA70 = $entity;
        $this->parent_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_taxonomy|null
     */
    public function getCFD811CA9557E6F6(bool $forceReload = false)
    {
        if ($this->CFD811CA9557E6F6 === null || $forceReload) {
            $this->CFD811CA9557E6F6 = sylius_taxonomyService::getInstance()->getEntityByPrimaryKey($this->taxonomy_id);
        }
        return $this->CFD811CA9557E6F6;
    }

    /**
     * @param sylius_taxonomy $entity
     * 
     * @return void
     */
    public function setCFD811CA9557E6F6(sylius_taxonomy $entity = null)
    {
        $this->CFD811CA9557E6F6 = $entity;
        $this->taxonomy_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_taxon $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_taxon $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}