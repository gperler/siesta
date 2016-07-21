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

class SyliusTaxonomy implements ArraySerializable
{

    const TABLE_NAME = "sylius_taxonomy";

    const COLUMN_ID = "id";

    const COLUMN_ROOTID = "root_id";

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
    protected $rootId;

    /**
     * @var SyliusTaxon
     */
    protected $2A9E3D279066886;

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
        $spCall = ($this->_existing) ? "CALL sylius_taxonomy_U(" : "CALL sylius_taxonomy_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->rootId) . ');';
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
        if ($cascade && $this->2A9E3D279066886 !== null) {
            $this->2A9E3D279066886->save($cascade, $cycleDetector, $connectionName);
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
        $this->rootId = $resultSet->getIntegerValue("root_id");
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
        $connection->execute("CALL sylius_taxonomy_DB_PK($id)");
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
        $this->setRootId($arrayAccessor->getIntegerValue("rootId"));
        $this->_existing = ($this->id !== null);
        $2A9E3D279066886Array = $arrayAccessor->getArray("2A9E3D279066886");
        if ($2A9E3D279066886Array !== null) {
            $2A9E3D279066886 = SyliusTaxonService::getInstance()->newInstance();
            $2A9E3D279066886->fromArray($2A9E3D279066886Array);
            $this->set2A9E3D279066886($2A9E3D279066886);
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
            "rootId" => $this->getRootId()
        ];
        if ($this->2A9E3D279066886 !== null) {
            $result["2A9E3D279066886"] = $this->2A9E3D279066886->toArray($cycleDetector);
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
    public function getRootId()
    {
        return $this->rootId;
    }

    /**
     * @param int $rootId
     * 
     * @return void
     */
    public function setRootId(int $rootId = null)
    {
        $this->rootId = $rootId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusTaxon|null
     */
    public function get2A9E3D279066886(bool $forceReload = false)
    {
        if ($this->2A9E3D279066886 === null || $forceReload) {
            $this->2A9E3D279066886 = SyliusTaxonService::getInstance()->getEntityByPrimaryKey($this->rootId);
        }
        return $this->2A9E3D279066886;
    }

    /**
     * @param SyliusTaxon $entity
     * 
     * @return void
     */
    public function set2A9E3D279066886(SyliusTaxon $entity = null)
    {
        $this->2A9E3D279066886 = $entity;
        $this->rootId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusTaxonomy $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusTaxonomy $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}