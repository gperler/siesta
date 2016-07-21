<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;

class sylius_channel_taxonomy implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_taxonomy";

    const COLUMN_CHANNEL_ID = "channel_id";

    const COLUMN_TAXONOMY_ID = "taxonomy_id";

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
    protected $channel_id;

    /**
     * @var int
     */
    protected $taxonomy_id;

    /**
     * @var sylius_channel
     */
    protected $4BE9652E72F5A1AA;

    /**
     * @var sylius_taxonomy
     */
    protected $4BE9652E9557E6F6;

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
        $spCall = ($this->_existing) ? "CALL sylius_channel_taxonomy_U(" : "CALL sylius_channel_taxonomy_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getChannel_id(true, $connectionName);
        $this->getTaxonomy_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channel_id) . ',' . Escaper::quoteInt($this->taxonomy_id) . ');';
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
        if ($cascade && $this->4BE9652E72F5A1AA !== null) {
            $this->4BE9652E72F5A1AA->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->4BE9652E9557E6F6 !== null) {
            $this->4BE9652E9557E6F6->save($cascade, $cycleDetector, $connectionName);
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
        $this->channel_id = $resultSet->getIntegerValue("channel_id");
        $this->taxonomy_id = $resultSet->getIntegerValue("taxonomy_id");
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
        $channel_id = Escaper::quoteInt($this->channel_id);
        $taxonomy_id = Escaper::quoteInt($this->taxonomy_id);
        $connection->execute("CALL sylius_channel_taxonomy_DB_PK($channel_id,$taxonomy_id)");
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
        $this->setChannel_id($arrayAccessor->getIntegerValue("channel_id"));
        $this->setTaxonomy_id($arrayAccessor->getIntegerValue("taxonomy_id"));
        $this->_existing = ($this->channel_id !== null) && ($this->taxonomy_id !== null);
        $4BE9652E72F5A1AAArray = $arrayAccessor->getArray("4BE9652E72F5A1AA");
        if ($4BE9652E72F5A1AAArray !== null) {
            $4BE9652E72F5A1AA = sylius_channelService::getInstance()->newInstance();
            $4BE9652E72F5A1AA->fromArray($4BE9652E72F5A1AAArray);
            $this->set4BE9652E72F5A1AA($4BE9652E72F5A1AA);
        }
        $4BE9652E9557E6F6Array = $arrayAccessor->getArray("4BE9652E9557E6F6");
        if ($4BE9652E9557E6F6Array !== null) {
            $4BE9652E9557E6F6 = sylius_taxonomyService::getInstance()->newInstance();
            $4BE9652E9557E6F6->fromArray($4BE9652E9557E6F6Array);
            $this->set4BE9652E9557E6F6($4BE9652E9557E6F6);
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
            "channel_id" => $this->getChannel_id(),
            "taxonomy_id" => $this->getTaxonomy_id()
        ];
        if ($this->4BE9652E72F5A1AA !== null) {
            $result["4BE9652E72F5A1AA"] = $this->4BE9652E72F5A1AA->toArray($cycleDetector);
        }
        if ($this->4BE9652E9557E6F6 !== null) {
            $result["4BE9652E9557E6F6"] = $this->4BE9652E9557E6F6->toArray($cycleDetector);
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
    public function getChannel_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->channel_id === null) {
            $this->channel_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->channel_id;
    }

    /**
     * @param int $channel_id
     * 
     * @return void
     */
    public function setChannel_id(int $channel_id = null)
    {
        $this->channel_id = $channel_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getTaxonomy_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->taxonomy_id === null) {
            $this->taxonomy_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function get4BE9652E72F5A1AA(bool $forceReload = false)
    {
        if ($this->4BE9652E72F5A1AA === null || $forceReload) {
            $this->4BE9652E72F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->4BE9652E72F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function set4BE9652E72F5A1AA(sylius_channel $entity = null)
    {
        $this->4BE9652E72F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_taxonomy|null
     */
    public function get4BE9652E9557E6F6(bool $forceReload = false)
    {
        if ($this->4BE9652E9557E6F6 === null || $forceReload) {
            $this->4BE9652E9557E6F6 = sylius_taxonomyService::getInstance()->getEntityByPrimaryKey($this->taxonomy_id);
        }
        return $this->4BE9652E9557E6F6;
    }

    /**
     * @param sylius_taxonomy $entity
     * 
     * @return void
     */
    public function set4BE9652E9557E6F6(sylius_taxonomy $entity = null)
    {
        $this->4BE9652E9557E6F6 = $entity;
        $this->taxonomy_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_channel_taxonomy $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_channel_taxonomy $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannel_id() === $entity->getChannel_id() && $this->getTaxonomy_id() === $entity->getTaxonomy_id();
    }

}