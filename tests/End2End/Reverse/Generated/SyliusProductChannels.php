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

class SyliusProductChannels implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_channels";

    const COLUMN_PRODUCTID = "product_id";

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
    protected $productId;

    /**
     * @var int
     */
    protected $channelId;

    /**
     * @var SyliusProduct
     */
    protected $F9EF269B4584665A;

    /**
     * @var SyliusChannel
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
        $this->getProductId(true, $connectionName);
        $this->getChannelId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->productId) . ',' . Escaper::quoteInt($this->channelId) . ');';
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
        $this->productId = $resultSet->getIntegerValue("product_id");
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
        $productId = Escaper::quoteInt($this->productId);
        $channelId = Escaper::quoteInt($this->channelId);
        $connection->execute("CALL sylius_product_channels_DB_PK($productId,$channelId)");
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
        $this->setProductId($arrayAccessor->getIntegerValue("productId"));
        $this->setChannelId($arrayAccessor->getIntegerValue("channelId"));
        $this->_existing = ($this->productId !== null) && ($this->channelId !== null);
        $F9EF269B4584665AArray = $arrayAccessor->getArray("F9EF269B4584665A");
        if ($F9EF269B4584665AArray !== null) {
            $F9EF269B4584665A = SyliusProductService::getInstance()->newInstance();
            $F9EF269B4584665A->fromArray($F9EF269B4584665AArray);
            $this->setF9EF269B4584665A($F9EF269B4584665A);
        }
        $F9EF269B72F5A1AAArray = $arrayAccessor->getArray("F9EF269B72F5A1AA");
        if ($F9EF269B72F5A1AAArray !== null) {
            $F9EF269B72F5A1AA = SyliusChannelService::getInstance()->newInstance();
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
            "productId" => $this->getProductId(),
            "channelId" => $this->getChannelId()
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
    public function getProductId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->productId === null) {
            $this->productId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->productId;
    }

    /**
     * @param int $productId
     * 
     * @return void
     */
    public function setProductId(int $productId = null)
    {
        $this->productId = $productId;
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
     * @return SyliusProduct|null
     */
    public function getF9EF269B4584665A(bool $forceReload = false)
    {
        if ($this->F9EF269B4584665A === null || $forceReload) {
            $this->F9EF269B4584665A = SyliusProductService::getInstance()->getEntityByPrimaryKey($this->productId);
        }
        return $this->F9EF269B4584665A;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return void
     */
    public function setF9EF269B4584665A(SyliusProduct $entity = null)
    {
        $this->F9EF269B4584665A = $entity;
        $this->productId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function getF9EF269B72F5A1AA(bool $forceReload = false)
    {
        if ($this->F9EF269B72F5A1AA === null || $forceReload) {
            $this->F9EF269B72F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->F9EF269B72F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function setF9EF269B72F5A1AA(SyliusChannel $entity = null)
    {
        $this->F9EF269B72F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductChannels $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductChannels $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProductId() === $entity->getProductId() && $this->getChannelId() === $entity->getChannelId();
    }

}