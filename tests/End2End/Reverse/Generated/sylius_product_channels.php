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

class sylius_product_channels implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_channels";

    const COLUMN_PRODUCT_ID = "product_id";

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
    protected $product_id;

    /**
     * @var int
     */
    protected $channel_id;

    /**
     * @var sylius_product
     */
    protected $F9EF269B4584665A;

    /**
     * @var sylius_channel
     */
    protected $F9EF269B72F5A1AA;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_channels_U(" : "CALL sylius_product_channels_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getProduct_id(true, $connectionName);
        $this->getChannel_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->product_id) . ',' . Escaper::quoteInt($this->channel_id) . ');';
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
        if ($cascade && $this->F9EF269B4584665A !== null) {
            $this->F9EF269B4584665A->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->F9EF269B72F5A1AA !== null) {
            $this->F9EF269B72F5A1AA->save($cascade, $cycleDetector, $connectionName);
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
        $this->product_id = $resultSet->getIntegerValue("product_id");
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
        $product_id = Escaper::quoteInt($this->product_id);
        $channel_id = Escaper::quoteInt($this->channel_id);
        $connection->execute("CALL sylius_product_channels_DB_PK($product_id,$channel_id)");
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
        $this->setProduct_id($arrayAccessor->getIntegerValue("product_id"));
        $this->setChannel_id($arrayAccessor->getIntegerValue("channel_id"));
        $this->_existing = ($this->product_id !== null) && ($this->channel_id !== null);
        $F9EF269B4584665AArray = $arrayAccessor->getArray("F9EF269B4584665A");
        if ($F9EF269B4584665AArray !== null) {
            $F9EF269B4584665A = sylius_productService::getInstance()->newInstance();
            $F9EF269B4584665A->fromArray($F9EF269B4584665AArray);
            $this->setF9EF269B4584665A($F9EF269B4584665A);
        }
        $F9EF269B72F5A1AAArray = $arrayAccessor->getArray("F9EF269B72F5A1AA");
        if ($F9EF269B72F5A1AAArray !== null) {
            $F9EF269B72F5A1AA = sylius_channelService::getInstance()->newInstance();
            $F9EF269B72F5A1AA->fromArray($F9EF269B72F5A1AAArray);
            $this->setF9EF269B72F5A1AA($F9EF269B72F5A1AA);
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
            "product_id" => $this->getProduct_id(),
            "channel_id" => $this->getChannel_id()
        ];
        if ($this->F9EF269B4584665A !== null) {
            $result["F9EF269B4584665A"] = $this->F9EF269B4584665A->toArray($cycleDetector);
        }
        if ($this->F9EF269B72F5A1AA !== null) {
            $result["F9EF269B72F5A1AA"] = $this->F9EF269B72F5A1AA->toArray($cycleDetector);
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
    public function getProduct_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->product_id === null) {
            $this->product_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->product_id;
    }

    /**
     * @param int $product_id
     * 
     * @return void
     */
    public function setProduct_id(int $product_id = null)
    {
        $this->product_id = $product_id;
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
     * @return sylius_product|null
     */
    public function getF9EF269B4584665A(bool $forceReload = false)
    {
        if ($this->F9EF269B4584665A === null || $forceReload) {
            $this->F9EF269B4584665A = sylius_productService::getInstance()->getEntityByPrimaryKey($this->product_id);
        }
        return $this->F9EF269B4584665A;
    }

    /**
     * @param sylius_product $entity
     * 
     * @return void
     */
    public function setF9EF269B4584665A(sylius_product $entity = null)
    {
        $this->F9EF269B4584665A = $entity;
        $this->product_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function getF9EF269B72F5A1AA(bool $forceReload = false)
    {
        if ($this->F9EF269B72F5A1AA === null || $forceReload) {
            $this->F9EF269B72F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->F9EF269B72F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function setF9EF269B72F5A1AA(sylius_channel $entity = null)
    {
        $this->F9EF269B72F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_channels $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_channels $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProduct_id() === $entity->getProduct_id() && $this->getChannel_id() === $entity->getChannel_id();
    }

}