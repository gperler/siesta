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

class SyliusChannelShippingMethods implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_shipping_methods";

    const COLUMN_CHANNELID = "channel_id";

    const COLUMN_SHIPPINGMETHODID = "shipping_method_id";

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
    protected $shippingMethodId;

    /**
     * @var SyliusShippingMethod
     */
    protected $6858B18E5F7D6850;

    /**
     * @var SyliusChannel
     */
    protected $6858B18E72F5A1AA;

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
        $spCall = ($this->_existing) ? "CALL sylius_channel_shipping_methods_U(" : "CALL sylius_channel_shipping_methods_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getChannelId(true, $connectionName);
        $this->getShippingMethodId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channelId) . ',' . Escaper::quoteInt($this->shippingMethodId) . ');';
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
        if ($cascade && $this->6858B18E5F7D6850 !== null) {
            $this->6858B18E5F7D6850->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->6858B18E72F5A1AA !== null) {
            $this->6858B18E72F5A1AA->save($cascade, $cycleDetector, $connectionName);
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
        $this->shippingMethodId = $resultSet->getIntegerValue("shipping_method_id");
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
        $shippingMethodId = Escaper::quoteInt($this->shippingMethodId);
        $connection->execute("CALL sylius_channel_shipping_methods_DB_PK($channelId,$shippingMethodId)");
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
        $this->setShippingMethodId($arrayAccessor->getIntegerValue("shippingMethodId"));
        $this->_existing = ($this->channelId !== null) && ($this->shippingMethodId !== null);
        $6858B18E5F7D6850Array = $arrayAccessor->getArray("6858B18E5F7D6850");
        if ($6858B18E5F7D6850Array !== null) {
            $6858B18E5F7D6850 = SyliusShippingMethodService::getInstance()->newInstance();
            $6858B18E5F7D6850->fromArray($6858B18E5F7D6850Array);
            $this->set6858B18E5F7D6850($6858B18E5F7D6850);
        }
        $6858B18E72F5A1AAArray = $arrayAccessor->getArray("6858B18E72F5A1AA");
        if ($6858B18E72F5A1AAArray !== null) {
            $6858B18E72F5A1AA = SyliusChannelService::getInstance()->newInstance();
            $6858B18E72F5A1AA->fromArray($6858B18E72F5A1AAArray);
            $this->set6858B18E72F5A1AA($6858B18E72F5A1AA);
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
            "shippingMethodId" => $this->getShippingMethodId()
        ];
        if ($this->6858B18E5F7D6850 !== null) {
            $result["6858B18E5F7D6850"] = $this->6858B18E5F7D6850->toArray($cycleDetector);
        }
        if ($this->6858B18E72F5A1AA !== null) {
            $result["6858B18E72F5A1AA"] = $this->6858B18E72F5A1AA->toArray($cycleDetector);
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
    public function getShippingMethodId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->shippingMethodId === null) {
            $this->shippingMethodId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->shippingMethodId;
    }

    /**
     * @param int $shippingMethodId
     * 
     * @return void
     */
    public function setShippingMethodId(int $shippingMethodId = null)
    {
        $this->shippingMethodId = $shippingMethodId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusShippingMethod|null
     */
    public function get6858B18E5F7D6850(bool $forceReload = false)
    {
        if ($this->6858B18E5F7D6850 === null || $forceReload) {
            $this->6858B18E5F7D6850 = SyliusShippingMethodService::getInstance()->getEntityByPrimaryKey($this->shippingMethodId);
        }
        return $this->6858B18E5F7D6850;
    }

    /**
     * @param SyliusShippingMethod $entity
     * 
     * @return void
     */
    public function set6858B18E5F7D6850(SyliusShippingMethod $entity = null)
    {
        $this->6858B18E5F7D6850 = $entity;
        $this->shippingMethodId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function get6858B18E72F5A1AA(bool $forceReload = false)
    {
        if ($this->6858B18E72F5A1AA === null || $forceReload) {
            $this->6858B18E72F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->6858B18E72F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function set6858B18E72F5A1AA(SyliusChannel $entity = null)
    {
        $this->6858B18E72F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusChannelShippingMethods $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusChannelShippingMethods $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannelId() === $entity->getChannelId() && $this->getShippingMethodId() === $entity->getShippingMethodId();
    }

}