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

class SyliusPromotionOrderItem implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_order_item";

    const COLUMN_ORDERITEMID = "order_item_id";

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
    protected $orderItemId;

    /**
     * @var int
     */
    protected $promotionId;

    /**
     * @var SyliusPromotion
     */
    protected $49838ED1139DF194;

    /**
     * @var SyliusOrderItem
     */
    protected $49838ED1E415FB15;

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
        $spCall = ($this->_existing) ? "CALL sylius_promotion_order_item_U(" : "CALL sylius_promotion_order_item_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getOrderItemId(true, $connectionName);
        $this->getPromotionId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->orderItemId) . ',' . Escaper::quoteInt($this->promotionId) . ');';
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
        if ($cascade && $this->49838ED1139DF194 !== null) {
            $this->49838ED1139DF194->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->49838ED1E415FB15 !== null) {
            $this->49838ED1E415FB15->save($cascade, $cycleDetector, $connectionName);
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
        $this->orderItemId = $resultSet->getIntegerValue("order_item_id");
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
        $orderItemId = Escaper::quoteInt($this->orderItemId);
        $promotionId = Escaper::quoteInt($this->promotionId);
        $connection->execute("CALL sylius_promotion_order_item_DB_PK($orderItemId,$promotionId)");
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
        $this->setOrderItemId($arrayAccessor->getIntegerValue("orderItemId"));
        $this->setPromotionId($arrayAccessor->getIntegerValue("promotionId"));
        $this->_existing = ($this->orderItemId !== null) && ($this->promotionId !== null);
        $49838ED1139DF194Array = $arrayAccessor->getArray("49838ED1139DF194");
        if ($49838ED1139DF194Array !== null) {
            $49838ED1139DF194 = SyliusPromotionService::getInstance()->newInstance();
            $49838ED1139DF194->fromArray($49838ED1139DF194Array);
            $this->set49838ED1139DF194($49838ED1139DF194);
        }
        $49838ED1E415FB15Array = $arrayAccessor->getArray("49838ED1E415FB15");
        if ($49838ED1E415FB15Array !== null) {
            $49838ED1E415FB15 = SyliusOrderItemService::getInstance()->newInstance();
            $49838ED1E415FB15->fromArray($49838ED1E415FB15Array);
            $this->set49838ED1E415FB15($49838ED1E415FB15);
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
            "orderItemId" => $this->getOrderItemId(),
            "promotionId" => $this->getPromotionId()
        ];
        if ($this->49838ED1139DF194 !== null) {
            $result["49838ED1139DF194"] = $this->49838ED1139DF194->toArray($cycleDetector);
        }
        if ($this->49838ED1E415FB15 !== null) {
            $result["49838ED1E415FB15"] = $this->49838ED1E415FB15->toArray($cycleDetector);
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
    public function getOrderItemId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->orderItemId === null) {
            $this->orderItemId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->orderItemId;
    }

    /**
     * @param int $orderItemId
     * 
     * @return void
     */
    public function setOrderItemId(int $orderItemId = null)
    {
        $this->orderItemId = $orderItemId;
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
    public function get49838ED1139DF194(bool $forceReload = false)
    {
        if ($this->49838ED1139DF194 === null || $forceReload) {
            $this->49838ED1139DF194 = SyliusPromotionService::getInstance()->getEntityByPrimaryKey($this->promotionId);
        }
        return $this->49838ED1139DF194;
    }

    /**
     * @param SyliusPromotion $entity
     * 
     * @return void
     */
    public function set49838ED1139DF194(SyliusPromotion $entity = null)
    {
        $this->49838ED1139DF194 = $entity;
        $this->promotionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrderItem|null
     */
    public function get49838ED1E415FB15(bool $forceReload = false)
    {
        if ($this->49838ED1E415FB15 === null || $forceReload) {
            $this->49838ED1E415FB15 = SyliusOrderItemService::getInstance()->getEntityByPrimaryKey($this->orderItemId);
        }
        return $this->49838ED1E415FB15;
    }

    /**
     * @param SyliusOrderItem $entity
     * 
     * @return void
     */
    public function set49838ED1E415FB15(SyliusOrderItem $entity = null)
    {
        $this->49838ED1E415FB15 = $entity;
        $this->orderItemId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusPromotionOrderItem $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPromotionOrderItem $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getOrderItemId() === $entity->getOrderItemId() && $this->getPromotionId() === $entity->getPromotionId();
    }

}