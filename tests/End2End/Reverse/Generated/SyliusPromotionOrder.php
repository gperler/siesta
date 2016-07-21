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

class SyliusPromotionOrder implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_order";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_PROMOTIONID = "promotion_id";

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
    protected $orderId;

    /**
     * @var int
     */
    protected $promotionId;

    /**
     * @var SyliusPromotion
     */
    protected $BF9CF6FB139DF194;

    /**
     * @var SyliusOrder
     */
    protected $BF9CF6FB8D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_promotion_order_U(" : "CALL sylius_promotion_order_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getOrderId(true, $connectionName);
        $this->getPromotionId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteInt($this->promotionId) . ');';
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
        if ($cascade && $this->BF9CF6FB139DF194 !== null) {
            $this->BF9CF6FB139DF194->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->BF9CF6FB8D9F6D38 !== null) {
            $this->BF9CF6FB8D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->orderId = $resultSet->getIntegerValue("order_id");
        $this->promotionId = $resultSet->getIntegerValue("promotion_id");
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
        $orderId = Escaper::quoteInt($this->orderId);
        $promotionId = Escaper::quoteInt($this->promotionId);
        $connection->execute("CALL sylius_promotion_order_DB_PK($orderId,$promotionId)");
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
        $this->setOrderId($arrayAccessor->getIntegerValue("orderId"));
        $this->setPromotionId($arrayAccessor->getIntegerValue("promotionId"));
        $this->_existing = ($this->orderId !== null) && ($this->promotionId !== null);
        $BF9CF6FB139DF194Array = $arrayAccessor->getArray("BF9CF6FB139DF194");
        if ($BF9CF6FB139DF194Array !== null) {
            $BF9CF6FB139DF194 = SyliusPromotionService::getInstance()->newInstance();
            $BF9CF6FB139DF194->fromArray($BF9CF6FB139DF194Array);
            $this->setBF9CF6FB139DF194($BF9CF6FB139DF194);
        }
        $BF9CF6FB8D9F6D38Array = $arrayAccessor->getArray("BF9CF6FB8D9F6D38");
        if ($BF9CF6FB8D9F6D38Array !== null) {
            $BF9CF6FB8D9F6D38 = SyliusOrderService::getInstance()->newInstance();
            $BF9CF6FB8D9F6D38->fromArray($BF9CF6FB8D9F6D38Array);
            $this->setBF9CF6FB8D9F6D38($BF9CF6FB8D9F6D38);
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
            "orderId" => $this->getOrderId(),
            "promotionId" => $this->getPromotionId()
        ];
        if ($this->BF9CF6FB139DF194 !== null) {
            $result["BF9CF6FB139DF194"] = $this->BF9CF6FB139DF194->toArray($cycleDetector);
        }
        if ($this->BF9CF6FB8D9F6D38 !== null) {
            $result["BF9CF6FB8D9F6D38"] = $this->BF9CF6FB8D9F6D38->toArray($cycleDetector);
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
    public function getOrderId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->orderId === null) {
            $this->orderId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->orderId;
    }

    /**
     * @param int $orderId
     * 
     * @return void
     */
    public function setOrderId(int $orderId = null)
    {
        $this->orderId = $orderId;
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
     * @param bool $forceReload
     * 
     * @return SyliusPromotion|null
     */
    public function getBF9CF6FB139DF194(bool $forceReload = false)
    {
        if ($this->BF9CF6FB139DF194 === null || $forceReload) {
            $this->BF9CF6FB139DF194 = SyliusPromotionService::getInstance()->getEntityByPrimaryKey($this->promotionId);
        }
        return $this->BF9CF6FB139DF194;
    }

    /**
     * @param SyliusPromotion $entity
     * 
     * @return void
     */
    public function setBF9CF6FB139DF194(SyliusPromotion $entity = null)
    {
        $this->BF9CF6FB139DF194 = $entity;
        $this->promotionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrder|null
     */
    public function getBF9CF6FB8D9F6D38(bool $forceReload = false)
    {
        if ($this->BF9CF6FB8D9F6D38 === null || $forceReload) {
            $this->BF9CF6FB8D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->BF9CF6FB8D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function setBF9CF6FB8D9F6D38(SyliusOrder $entity = null)
    {
        $this->BF9CF6FB8D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusPromotionOrder $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPromotionOrder $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getOrderId() === $entity->getOrderId() && $this->getPromotionId() === $entity->getPromotionId();
    }

}