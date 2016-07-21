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

class SyliusChannelPaymentMethods implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_payment_methods";

    const COLUMN_CHANNELID = "channel_id";

    const COLUMN_PAYMENTMETHODID = "payment_method_id";

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
    protected $paymentMethodId;

    /**
     * @var SyliusPaymentMethod
     */
    protected $B0C0002B5AA1164F;

    /**
     * @var SyliusChannel
     */
    protected $B0C0002B72F5A1AA;

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
        $spCall = ($this->_existing) ? "CALL sylius_channel_payment_methods_U(" : "CALL sylius_channel_payment_methods_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getChannelId(true, $connectionName);
        $this->getPaymentMethodId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channelId) . ',' . Escaper::quoteInt($this->paymentMethodId) . ');';
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
        if ($cascade && $this->B0C0002B5AA1164F !== null) {
            $this->B0C0002B5AA1164F->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->B0C0002B72F5A1AA !== null) {
            $this->B0C0002B72F5A1AA->save($cascade, $cycleDetector, $connectionName);
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
        $this->paymentMethodId = $resultSet->getIntegerValue("payment_method_id");
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
        $paymentMethodId = Escaper::quoteInt($this->paymentMethodId);
        $connection->execute("CALL sylius_channel_payment_methods_DB_PK($channelId,$paymentMethodId)");
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
        $this->setPaymentMethodId($arrayAccessor->getIntegerValue("paymentMethodId"));
        $this->_existing = ($this->channelId !== null) && ($this->paymentMethodId !== null);
        $B0C0002B5AA1164FArray = $arrayAccessor->getArray("B0C0002B5AA1164F");
        if ($B0C0002B5AA1164FArray !== null) {
            $B0C0002B5AA1164F = SyliusPaymentMethodService::getInstance()->newInstance();
            $B0C0002B5AA1164F->fromArray($B0C0002B5AA1164FArray);
            $this->setB0C0002B5AA1164F($B0C0002B5AA1164F);
        }
        $B0C0002B72F5A1AAArray = $arrayAccessor->getArray("B0C0002B72F5A1AA");
        if ($B0C0002B72F5A1AAArray !== null) {
            $B0C0002B72F5A1AA = SyliusChannelService::getInstance()->newInstance();
            $B0C0002B72F5A1AA->fromArray($B0C0002B72F5A1AAArray);
            $this->setB0C0002B72F5A1AA($B0C0002B72F5A1AA);
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
            "paymentMethodId" => $this->getPaymentMethodId()
        ];
        if ($this->B0C0002B5AA1164F !== null) {
            $result["B0C0002B5AA1164F"] = $this->B0C0002B5AA1164F->toArray($cycleDetector);
        }
        if ($this->B0C0002B72F5A1AA !== null) {
            $result["B0C0002B72F5A1AA"] = $this->B0C0002B72F5A1AA->toArray($cycleDetector);
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
    public function getPaymentMethodId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->paymentMethodId === null) {
            $this->paymentMethodId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->paymentMethodId;
    }

    /**
     * @param int $paymentMethodId
     * 
     * @return void
     */
    public function setPaymentMethodId(int $paymentMethodId = null)
    {
        $this->paymentMethodId = $paymentMethodId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusPaymentMethod|null
     */
    public function getB0C0002B5AA1164F(bool $forceReload = false)
    {
        if ($this->B0C0002B5AA1164F === null || $forceReload) {
            $this->B0C0002B5AA1164F = SyliusPaymentMethodService::getInstance()->getEntityByPrimaryKey($this->paymentMethodId);
        }
        return $this->B0C0002B5AA1164F;
    }

    /**
     * @param SyliusPaymentMethod $entity
     * 
     * @return void
     */
    public function setB0C0002B5AA1164F(SyliusPaymentMethod $entity = null)
    {
        $this->B0C0002B5AA1164F = $entity;
        $this->paymentMethodId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function getB0C0002B72F5A1AA(bool $forceReload = false)
    {
        if ($this->B0C0002B72F5A1AA === null || $forceReload) {
            $this->B0C0002B72F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->B0C0002B72F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function setB0C0002B72F5A1AA(SyliusChannel $entity = null)
    {
        $this->B0C0002B72F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusChannelPaymentMethods $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusChannelPaymentMethods $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannelId() === $entity->getChannelId() && $this->getPaymentMethodId() === $entity->getPaymentMethodId();
    }

}