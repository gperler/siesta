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

class SyliusChannelCurrencies implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_currencies";

    const COLUMN_CHANNELID = "channel_id";

    const COLUMN_CURRENCYID = "currency_id";

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
    protected $channelId;

    /**
     * @var int
     */
    protected $currencyId;

    /**
     * @var SyliusCurrency
     */
    protected $AE491F9338248176;

    /**
     * @var SyliusChannel
     */
    protected $AE491F9372F5A1AA;

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
        $spCall = ($this->_existing) ? "CALL sylius_channel_currencies_U(" : "CALL sylius_channel_currencies_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getChannelId(true, $connectionName);
        $this->getCurrencyId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channelId) . ',' . Escaper::quoteInt($this->currencyId) . ');';
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
        if ($cascade && $this->AE491F9338248176 !== null) {
            $this->AE491F9338248176->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->AE491F9372F5A1AA !== null) {
            $this->AE491F9372F5A1AA->save($cascade, $cycleDetector, $connectionName);
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
        $this->channelId = $resultSet->getIntegerValue("channel_id");
        $this->currencyId = $resultSet->getIntegerValue("currency_id");
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
        $channelId = Escaper::quoteInt($this->channelId);
        $currencyId = Escaper::quoteInt($this->currencyId);
        $connection->execute("CALL sylius_channel_currencies_DB_PK($channelId,$currencyId)");
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
        $this->setChannelId($arrayAccessor->getIntegerValue("channelId"));
        $this->setCurrencyId($arrayAccessor->getIntegerValue("currencyId"));
        $this->_existing = ($this->channelId !== null) && ($this->currencyId !== null);
        $AE491F9338248176Array = $arrayAccessor->getArray("AE491F9338248176");
        if ($AE491F9338248176Array !== null) {
            $AE491F9338248176 = SyliusCurrencyService::getInstance()->newInstance();
            $AE491F9338248176->fromArray($AE491F9338248176Array);
            $this->setAE491F9338248176($AE491F9338248176);
        }
        $AE491F9372F5A1AAArray = $arrayAccessor->getArray("AE491F9372F5A1AA");
        if ($AE491F9372F5A1AAArray !== null) {
            $AE491F9372F5A1AA = SyliusChannelService::getInstance()->newInstance();
            $AE491F9372F5A1AA->fromArray($AE491F9372F5A1AAArray);
            $this->setAE491F9372F5A1AA($AE491F9372F5A1AA);
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
            "channelId" => $this->getChannelId(),
            "currencyId" => $this->getCurrencyId()
        ];
        if ($this->AE491F9338248176 !== null) {
            $result["AE491F9338248176"] = $this->AE491F9338248176->toArray($cycleDetector);
        }
        if ($this->AE491F9372F5A1AA !== null) {
            $result["AE491F9372F5A1AA"] = $this->AE491F9372F5A1AA->toArray($cycleDetector);
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
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getCurrencyId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->currencyId === null) {
            $this->currencyId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->currencyId;
    }

    /**
     * @param int $currencyId
     * 
     * @return void
     */
    public function setCurrencyId(int $currencyId = null)
    {
        $this->currencyId = $currencyId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCurrency|null
     */
    public function getAE491F9338248176(bool $forceReload = false)
    {
        if ($this->AE491F9338248176 === null || $forceReload) {
            $this->AE491F9338248176 = SyliusCurrencyService::getInstance()->getEntityByPrimaryKey($this->currencyId);
        }
        return $this->AE491F9338248176;
    }

    /**
     * @param SyliusCurrency $entity
     * 
     * @return void
     */
    public function setAE491F9338248176(SyliusCurrency $entity = null)
    {
        $this->AE491F9338248176 = $entity;
        $this->currencyId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function getAE491F9372F5A1AA(bool $forceReload = false)
    {
        if ($this->AE491F9372F5A1AA === null || $forceReload) {
            $this->AE491F9372F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->AE491F9372F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function setAE491F9372F5A1AA(SyliusChannel $entity = null)
    {
        $this->AE491F9372F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusChannelCurrencies $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusChannelCurrencies $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannelId() === $entity->getChannelId() && $this->getCurrencyId() === $entity->getCurrencyId();
    }

}