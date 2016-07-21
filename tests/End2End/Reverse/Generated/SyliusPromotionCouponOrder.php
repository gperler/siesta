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

class SyliusPromotionCouponOrder implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_coupon_order";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_PROMOTIONCOUPONID = "promotion_coupon_id";

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
    protected $promotionCouponId;

    /**
     * @var SyliusPromotionCoupon
     */
    protected $D58E3BC417B24436;

    /**
     * @var SyliusOrder
     */
    protected $D58E3BC48D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_promotion_coupon_order_U(" : "CALL sylius_promotion_coupon_order_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getOrderId(true, $connectionName);
        $this->getPromotionCouponId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteInt($this->promotionCouponId) . ');';
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
        if ($cascade && $this->D58E3BC417B24436 !== null) {
            $this->D58E3BC417B24436->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->D58E3BC48D9F6D38 !== null) {
            $this->D58E3BC48D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->promotionCouponId = $resultSet->getIntegerValue("promotion_coupon_id");
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
        $promotionCouponId = Escaper::quoteInt($this->promotionCouponId);
        $connection->execute("CALL sylius_promotion_coupon_order_DB_PK($orderId,$promotionCouponId)");
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
        $this->setPromotionCouponId($arrayAccessor->getIntegerValue("promotionCouponId"));
        $this->_existing = ($this->orderId !== null) && ($this->promotionCouponId !== null);
        $D58E3BC417B24436Array = $arrayAccessor->getArray("D58E3BC417B24436");
        if ($D58E3BC417B24436Array !== null) {
            $D58E3BC417B24436 = SyliusPromotionCouponService::getInstance()->newInstance();
            $D58E3BC417B24436->fromArray($D58E3BC417B24436Array);
            $this->setD58E3BC417B24436($D58E3BC417B24436);
        }
        $D58E3BC48D9F6D38Array = $arrayAccessor->getArray("D58E3BC48D9F6D38");
        if ($D58E3BC48D9F6D38Array !== null) {
            $D58E3BC48D9F6D38 = SyliusOrderService::getInstance()->newInstance();
            $D58E3BC48D9F6D38->fromArray($D58E3BC48D9F6D38Array);
            $this->setD58E3BC48D9F6D38($D58E3BC48D9F6D38);
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
            "promotionCouponId" => $this->getPromotionCouponId()
        ];
        if ($this->D58E3BC417B24436 !== null) {
            $result["D58E3BC417B24436"] = $this->D58E3BC417B24436->toArray($cycleDetector);
        }
        if ($this->D58E3BC48D9F6D38 !== null) {
            $result["D58E3BC48D9F6D38"] = $this->D58E3BC48D9F6D38->toArray($cycleDetector);
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
    public function getPromotionCouponId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->promotionCouponId === null) {
            $this->promotionCouponId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->promotionCouponId;
    }

    /**
     * @param int $promotionCouponId
     * 
     * @return void
     */
    public function setPromotionCouponId(int $promotionCouponId = null)
    {
        $this->promotionCouponId = $promotionCouponId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusPromotionCoupon|null
     */
    public function getD58E3BC417B24436(bool $forceReload = false)
    {
        if ($this->D58E3BC417B24436 === null || $forceReload) {
            $this->D58E3BC417B24436 = SyliusPromotionCouponService::getInstance()->getEntityByPrimaryKey($this->promotionCouponId);
        }
        return $this->D58E3BC417B24436;
    }

    /**
     * @param SyliusPromotionCoupon $entity
     * 
     * @return void
     */
    public function setD58E3BC417B24436(SyliusPromotionCoupon $entity = null)
    {
        $this->D58E3BC417B24436 = $entity;
        $this->promotionCouponId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrder|null
     */
    public function getD58E3BC48D9F6D38(bool $forceReload = false)
    {
        if ($this->D58E3BC48D9F6D38 === null || $forceReload) {
            $this->D58E3BC48D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->D58E3BC48D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function setD58E3BC48D9F6D38(SyliusOrder $entity = null)
    {
        $this->D58E3BC48D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusPromotionCouponOrder $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPromotionCouponOrder $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getOrderId() === $entity->getOrderId() && $this->getPromotionCouponId() === $entity->getPromotionCouponId();
    }

}