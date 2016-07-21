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

class sylius_channel_shipping_methods implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_shipping_methods";

    const COLUMN_CHANNEL_ID = "channel_id";

    const COLUMN_SHIPPING_METHOD_ID = "shipping_method_id";

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
    protected $shipping_method_id;

    /**
     * @var sylius_shipping_method
     */
    protected $6858B18E5F7D6850;

    /**
     * @var sylius_channel
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
        $this->getChannel_id(true, $connectionName);
        $this->getShipping_method_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channel_id) . ',' . Escaper::quoteInt($this->shipping_method_id) . ');';
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
        $this->channel_id = $resultSet->getIntegerValue("channel_id");
        $this->shipping_method_id = $resultSet->getIntegerValue("shipping_method_id");
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
        $shipping_method_id = Escaper::quoteInt($this->shipping_method_id);
        $connection->execute("CALL sylius_channel_shipping_methods_DB_PK($channel_id,$shipping_method_id)");
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
        $this->setShipping_method_id($arrayAccessor->getIntegerValue("shipping_method_id"));
        $this->_existing = ($this->channel_id !== null) && ($this->shipping_method_id !== null);
        $6858B18E5F7D6850Array = $arrayAccessor->getArray("6858B18E5F7D6850");
        if ($6858B18E5F7D6850Array !== null) {
            $6858B18E5F7D6850 = sylius_shipping_methodService::getInstance()->newInstance();
            $6858B18E5F7D6850->fromArray($6858B18E5F7D6850Array);
            $this->set6858B18E5F7D6850($6858B18E5F7D6850);
        }
        $6858B18E72F5A1AAArray = $arrayAccessor->getArray("6858B18E72F5A1AA");
        if ($6858B18E72F5A1AAArray !== null) {
            $6858B18E72F5A1AA = sylius_channelService::getInstance()->newInstance();
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
            "channel_id" => $this->getChannel_id(),
            "shipping_method_id" => $this->getShipping_method_id()
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
    public function getShipping_method_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->shipping_method_id === null) {
            $this->shipping_method_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->shipping_method_id;
    }

    /**
     * @param int $shipping_method_id
     * 
     * @return void
     */
    public function setShipping_method_id(int $shipping_method_id = null)
    {
        $this->shipping_method_id = $shipping_method_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_shipping_method|null
     */
    public function get6858B18E5F7D6850(bool $forceReload = false)
    {
        if ($this->6858B18E5F7D6850 === null || $forceReload) {
            $this->6858B18E5F7D6850 = sylius_shipping_methodService::getInstance()->getEntityByPrimaryKey($this->shipping_method_id);
        }
        return $this->6858B18E5F7D6850;
    }

    /**
     * @param sylius_shipping_method $entity
     * 
     * @return void
     */
    public function set6858B18E5F7D6850(sylius_shipping_method $entity = null)
    {
        $this->6858B18E5F7D6850 = $entity;
        $this->shipping_method_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function get6858B18E72F5A1AA(bool $forceReload = false)
    {
        if ($this->6858B18E72F5A1AA === null || $forceReload) {
            $this->6858B18E72F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->6858B18E72F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function set6858B18E72F5A1AA(sylius_channel $entity = null)
    {
        $this->6858B18E72F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_channel_shipping_methods $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_channel_shipping_methods $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannel_id() === $entity->getChannel_id() && $this->getShipping_method_id() === $entity->getShipping_method_id();
    }

}