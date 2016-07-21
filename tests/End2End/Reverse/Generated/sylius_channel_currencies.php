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

class sylius_channel_currencies implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_currencies";

    const COLUMN_CHANNEL_ID = "channel_id";

    const COLUMN_CURRENCY_ID = "currency_id";

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
    protected $currency_id;

    /**
     * @var sylius_currency
     */
    protected $AE491F9338248176;

    /**
     * @var sylius_channel
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
        $this->getChannel_id(true, $connectionName);
        $this->getCurrency_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channel_id) . ',' . Escaper::quoteInt($this->currency_id) . ');';
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
        $this->channel_id = $resultSet->getIntegerValue("channel_id");
        $this->currency_id = $resultSet->getIntegerValue("currency_id");
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
        $currency_id = Escaper::quoteInt($this->currency_id);
        $connection->execute("CALL sylius_channel_currencies_DB_PK($channel_id,$currency_id)");
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
        $this->setCurrency_id($arrayAccessor->getIntegerValue("currency_id"));
        $this->_existing = ($this->channel_id !== null) && ($this->currency_id !== null);
        $AE491F9338248176Array = $arrayAccessor->getArray("AE491F9338248176");
        if ($AE491F9338248176Array !== null) {
            $AE491F9338248176 = sylius_currencyService::getInstance()->newInstance();
            $AE491F9338248176->fromArray($AE491F9338248176Array);
            $this->setAE491F9338248176($AE491F9338248176);
        }
        $AE491F9372F5A1AAArray = $arrayAccessor->getArray("AE491F9372F5A1AA");
        if ($AE491F9372F5A1AAArray !== null) {
            $AE491F9372F5A1AA = sylius_channelService::getInstance()->newInstance();
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
            "channel_id" => $this->getChannel_id(),
            "currency_id" => $this->getCurrency_id()
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
    public function getCurrency_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->currency_id === null) {
            $this->currency_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->currency_id;
    }

    /**
     * @param int $currency_id
     * 
     * @return void
     */
    public function setCurrency_id(int $currency_id = null)
    {
        $this->currency_id = $currency_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_currency|null
     */
    public function getAE491F9338248176(bool $forceReload = false)
    {
        if ($this->AE491F9338248176 === null || $forceReload) {
            $this->AE491F9338248176 = sylius_currencyService::getInstance()->getEntityByPrimaryKey($this->currency_id);
        }
        return $this->AE491F9338248176;
    }

    /**
     * @param sylius_currency $entity
     * 
     * @return void
     */
    public function setAE491F9338248176(sylius_currency $entity = null)
    {
        $this->AE491F9338248176 = $entity;
        $this->currency_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function getAE491F9372F5A1AA(bool $forceReload = false)
    {
        if ($this->AE491F9372F5A1AA === null || $forceReload) {
            $this->AE491F9372F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->AE491F9372F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function setAE491F9372F5A1AA(sylius_channel $entity = null)
    {
        $this->AE491F9372F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_channel_currencies $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_channel_currencies $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannel_id() === $entity->getChannel_id() && $this->getCurrency_id() === $entity->getCurrency_id();
    }

}