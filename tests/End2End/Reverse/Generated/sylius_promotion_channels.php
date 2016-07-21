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

class sylius_promotion_channels implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_channels";

    const COLUMN_PROMOTION_ID = "promotion_id";

    const COLUMN_CHANNEL_ID = "channel_id";

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
    protected $promotion_id;

    /**
     * @var int
     */
    protected $channel_id;

    /**
     * @var sylius_promotion
     */
    protected $1A044F64139DF194;

    /**
     * @var sylius_channel
     */
    protected $1A044F6472F5A1AA;

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
        $spCall = ($this->_existing) ? "CALL sylius_promotion_channels_U(" : "CALL sylius_promotion_channels_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getPromotion_id(true, $connectionName);
        $this->getChannel_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->promotion_id) . ',' . Escaper::quoteInt($this->channel_id) . ');';
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
        if ($cascade && $this->1A044F64139DF194 !== null) {
            $this->1A044F64139DF194->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->1A044F6472F5A1AA !== null) {
            $this->1A044F6472F5A1AA->save($cascade, $cycleDetector, $connectionName);
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
        $this->promotion_id = $resultSet->getIntegerValue("promotion_id");
        $this->channel_id = $resultSet->getIntegerValue("channel_id");
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
        $promotion_id = Escaper::quoteInt($this->promotion_id);
        $channel_id = Escaper::quoteInt($this->channel_id);
        $connection->execute("CALL sylius_promotion_channels_DB_PK($promotion_id,$channel_id)");
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
        $this->setPromotion_id($arrayAccessor->getIntegerValue("promotion_id"));
        $this->setChannel_id($arrayAccessor->getIntegerValue("channel_id"));
        $this->_existing = ($this->promotion_id !== null) && ($this->channel_id !== null);
        $1A044F64139DF194Array = $arrayAccessor->getArray("1A044F64139DF194");
        if ($1A044F64139DF194Array !== null) {
            $1A044F64139DF194 = sylius_promotionService::getInstance()->newInstance();
            $1A044F64139DF194->fromArray($1A044F64139DF194Array);
            $this->set1A044F64139DF194($1A044F64139DF194);
        }
        $1A044F6472F5A1AAArray = $arrayAccessor->getArray("1A044F6472F5A1AA");
        if ($1A044F6472F5A1AAArray !== null) {
            $1A044F6472F5A1AA = sylius_channelService::getInstance()->newInstance();
            $1A044F6472F5A1AA->fromArray($1A044F6472F5A1AAArray);
            $this->set1A044F6472F5A1AA($1A044F6472F5A1AA);
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
            "promotion_id" => $this->getPromotion_id(),
            "channel_id" => $this->getChannel_id()
        ];
        if ($this->1A044F64139DF194 !== null) {
            $result["1A044F64139DF194"] = $this->1A044F64139DF194->toArray($cycleDetector);
        }
        if ($this->1A044F6472F5A1AA !== null) {
            $result["1A044F6472F5A1AA"] = $this->1A044F6472F5A1AA->toArray($cycleDetector);
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
    public function getPromotion_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->promotion_id === null) {
            $this->promotion_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->promotion_id;
    }

    /**
     * @param int $promotion_id
     * 
     * @return void
     */
    public function setPromotion_id(int $promotion_id = null)
    {
        $this->promotion_id = $promotion_id;
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
     * @param bool $forceReload
     * 
     * @return sylius_promotion|null
     */
    public function get1A044F64139DF194(bool $forceReload = false)
    {
        if ($this->1A044F64139DF194 === null || $forceReload) {
            $this->1A044F64139DF194 = sylius_promotionService::getInstance()->getEntityByPrimaryKey($this->promotion_id);
        }
        return $this->1A044F64139DF194;
    }

    /**
     * @param sylius_promotion $entity
     * 
     * @return void
     */
    public function set1A044F64139DF194(sylius_promotion $entity = null)
    {
        $this->1A044F64139DF194 = $entity;
        $this->promotion_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function get1A044F6472F5A1AA(bool $forceReload = false)
    {
        if ($this->1A044F6472F5A1AA === null || $forceReload) {
            $this->1A044F6472F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->1A044F6472F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function set1A044F6472F5A1AA(sylius_channel $entity = null)
    {
        $this->1A044F6472F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_promotion_channels $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_promotion_channels $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getPromotion_id() === $entity->getPromotion_id() && $this->getChannel_id() === $entity->getChannel_id();
    }

}