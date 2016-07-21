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

class SyliusTaxon implements ArraySerializable
{

    const TABLE_NAME = "sylius_taxon";

    const COLUMN_ID = "id";

    const COLUMN_TAXONOMYID = "taxonomy_id";

    const COLUMN_PARENTID = "parent_id";

    const COLUMN_TREELEFT = "tree_left";

    const COLUMN_TREERIGHT = "tree_right";

    const COLUMN_TREELEVEL = "tree_level";

    const COLUMN_DELETEDAT = "deleted_at";

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
    protected $taxonomyId;

    /**
     * @var int
     */
    protected $parentId;

    /**
     * @var int
     */
    protected $treeLeft;

    /**
     * @var int
     */
    protected $treeRight;

    /**
     * @var int
     */
    protected $treeLevel;

    /**
     * @var SiestaDateTime
     */
    protected $deletedAt;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var SyliusTaxon
     */
    protected $CFD811CA727ACA70;

    /**
     * @var SyliusTaxonomy
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->taxonomyId) . ',' . Escaper::quoteInt($this->parentId) . ',' . Escaper::quoteInt($this->treeLeft) . ',' . Escaper::quoteInt($this->treeRight) . ',' . Escaper::quoteInt($this->treeLevel) . ',' . Escaper::quoteDateTime($this->deletedAt) . ',' . Escaper::quoteString($connection, $this->path) . ');';
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
        $this->taxonomyId = $resultSet->getIntegerValue("taxonomy_id");
        $this->parentId = $resultSet->getIntegerValue("parent_id");
        $this->treeLeft = $resultSet->getIntegerValue("tree_left");
        $this->treeRight = $resultSet->getIntegerValue("tree_right");
        $this->treeLevel = $resultSet->getIntegerValue("tree_level");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
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
        $this->setTaxonomyId($arrayAccessor->getIntegerValue("taxonomyId"));
        $this->setParentId($arrayAccessor->getIntegerValue("parentId"));
        $this->setTreeLeft($arrayAccessor->getIntegerValue("treeLeft"));
        $this->setTreeRight($arrayAccessor->getIntegerValue("treeRight"));
        $this->setTreeLevel($arrayAccessor->getIntegerValue("treeLevel"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->setPath($arrayAccessor->getStringValue("path"));
        $this->_existing = ($this->id !== null);
        $CFD811CA727ACA70Array = $arrayAccessor->getArray("CFD811CA727ACA70");
        if ($CFD811CA727ACA70Array !== null) {
            $CFD811CA727ACA70 = SyliusTaxonService::getInstance()->newInstance();
            $CFD811CA727ACA70->fromArray($CFD811CA727ACA70Array);
            $this->setCFD811CA727ACA70($CFD811CA727ACA70);
        }
        $CFD811CA9557E6F6Array = $arrayAccessor->getArray("CFD811CA9557E6F6");
        if ($CFD811CA9557E6F6Array !== null) {
            $CFD811CA9557E6F6 = SyliusTaxonomyService::getInstance()->newInstance();
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
            "taxonomyId" => $this->getTaxonomyId(),
            "parentId" => $this->getParentId(),
            "treeLeft" => $this->getTreeLeft(),
            "treeRight" => $this->getTreeRight(),
            "treeLevel" => $this->getTreeLevel(),
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null,
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
    public function getTaxonomyId()
    {
        return $this->taxonomyId;
    }

    /**
     * @param int $taxonomyId
     * 
     * @return void
     */
    public function setTaxonomyId(int $taxonomyId = null)
    {
        $this->taxonomyId = $taxonomyId;
    }

    /**
     * 
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     * 
     * @return void
     */
    public function setParentId(int $parentId = null)
    {
        $this->parentId = $parentId;
    }

    /**
     * 
     * @return int|null
     */
    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    /**
     * @param int $treeLeft
     * 
     * @return void
     */
    public function setTreeLeft(int $treeLeft = null)
    {
        $this->treeLeft = $treeLeft;
    }

    /**
     * 
     * @return int|null
     */
    public function getTreeRight()
    {
        return $this->treeRight;
    }

    /**
     * @param int $treeRight
     * 
     * @return void
     */
    public function setTreeRight(int $treeRight = null)
    {
        $this->treeRight = $treeRight;
    }

    /**
     * 
     * @return int|null
     */
    public function getTreeLevel()
    {
        return $this->treeLevel;
    }

    /**
     * @param int $treeLevel
     * 
     * @return void
     */
    public function setTreeLevel(int $treeLevel = null)
    {
        $this->treeLevel = $treeLevel;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param SiestaDateTime $deletedAt
     * 
     * @return void
     */
    public function setDeletedAt(SiestaDateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
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
     * @return SyliusTaxon|null
     */
    public function getCFD811CA727ACA70(bool $forceReload = false)
    {
        if ($this->CFD811CA727ACA70 === null || $forceReload) {
            $this->CFD811CA727ACA70 = SyliusTaxonService::getInstance()->getEntityByPrimaryKey($this->parentId);
        }
        return $this->CFD811CA727ACA70;
    }

    /**
     * @param SyliusTaxon $entity
     * 
     * @return void
     */
    public function setCFD811CA727ACA70(SyliusTaxon $entity = null)
    {
        $this->CFD811CA727ACA70 = $entity;
        $this->parentId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusTaxonomy|null
     */
    public function getCFD811CA9557E6F6(bool $forceReload = false)
    {
        if ($this->CFD811CA9557E6F6 === null || $forceReload) {
            $this->CFD811CA9557E6F6 = SyliusTaxonomyService::getInstance()->getEntityByPrimaryKey($this->taxonomyId);
        }
        return $this->CFD811CA9557E6F6;
    }

    /**
     * @param SyliusTaxonomy $entity
     * 
     * @return void
     */
    public function setCFD811CA9557E6F6(SyliusTaxonomy $entity = null)
    {
        $this->CFD811CA9557E6F6 = $entity;
        $this->taxonomyId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusTaxon $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusTaxon $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}