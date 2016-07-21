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

class SyliusPromotionChannels implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_channels";

    const COLUMN_PROMOTIONID = "promotion_id";

    const COLUMN_CHANNELID = "channel_id";

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
    protected $promotionId;

    /**
     * @var int
     */
    protected $channelId;

    /**
     * @var SyliusPromotion
     */
    protected $1A044F64139DF194;

    /**
     * @var SyliusChannel
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
        $this->getPromotionId(true, $connectionName);
        $this->getChannelId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->promotionId) . ',' . Escaper::quoteInt($this->channelId) . ');';
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
        $this->promotionId = $resultSet->getIntegerValue("promotion_id");
        $this->channelId = $resultSet->getIntegerValue("channel_id");
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
        $promotionId = Escaper::quoteInt($this->promotionId);
        $channelId = Escaper::quoteInt($this->channelId);
        $connection->execute("CALL sylius_promotion_channels_DB_PK($promotionId,$channelId)");
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
        $this->setPromotionId($arrayAccessor->getIntegerValue("promotionId"));
        $this->setChannelId($arrayAccessor->getIntegerValue("channelId"));
        $this->_existing = ($this->promotionId !== null) && ($this->channelId !== null);
        $1A044F64139DF194Array = $arrayAccessor->getArray("1A044F64139DF194");
        if ($1A044F64139DF194Array !== null) {
            $1A044F64139DF194 = SyliusPromotionService::getInstance()->newInstance();
            $1A044F64139DF194->fromArray($1A044F64139DF194Array);
            $this->set1A044F64139DF194($1A044F64139DF194);
        }
        $1A044F6472F5A1AAArray = $arrayAccessor->getArray("1A044F6472F5A1AA");
        if ($1A044F6472F5A1AAArray !== null) {
            $1A044F6472F5A1AA = SyliusChannelService::getInstance()->newInstance();
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
            "promotionId" => $this->getPromotionId(),
            "channelId" => $this->getChannelId()
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
    public function getPromotionId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->promotionId === null) {
            $this->promotionId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->promotionId;
    }

    /**
     * @param int $promotionId
     * 
     * @return void
     */
    public function setPromotionId(int $promotionId = null)
    {
        $this->promotionId = $promotionId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getChannelId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->channelId === null) {
            $this->channelId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->channelId;
    }

    /**
     * @param int $channelId
     * 
     * @return void
     */
    public function setChannelId(int $channelId = null)
    {
        $this->channelId = $channelId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusPromotion|null
     */
    public function get1A044F64139DF194(bool $forceReload = false)
    {
        if ($this->1A044F64139DF194 === null || $forceReload) {
            $this->1A044F64139DF194 = SyliusPromotionService::getInstance()->getEntityByPrimaryKey($this->promotionId);
        }
        return $this->1A044F64139DF194;
    }

    /**
     * @param SyliusPromotion $entity
     * 
     * @return void
     */
    public function set1A044F64139DF194(SyliusPromotion $entity = null)
    {
        $this->1A044F64139DF194 = $entity;
        $this->promotionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function get1A044F6472F5A1AA(bool $forceReload = false)
    {
        if ($this->1A044F6472F5A1AA === null || $forceReload) {
            $this->1A044F6472F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->1A044F6472F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function set1A044F6472F5A1AA(SyliusChannel $entity = null)
    {
        $this->1A044F6472F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusPromotionChannels $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPromotionChannels $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getPromotionId() === $entity->getPromotionId() && $this->getChannelId() === $entity->getChannelId();
    }

}