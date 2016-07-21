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

class sylius_promotion_order_item implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_order_item";

    const COLUMN_ORDER_ITEM_ID = "order_item_id";

    const COLUMN_PROMOTION_ID = "promotion_id";

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
    protected $order_item_id;

    /**
     * @var int
     */
    protected $promotion_id;

    /**
     * @var sylius_promotion
     */
    protected $49838ED1139DF194;

    /**
     * @var sylius_order_item
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
        $this->getOrder_item_id(true, $connectionName);
        $this->getPromotion_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->order_item_id) . ',' . Escaper::quoteInt($this->promotion_id) . ');';
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
        $this->order_item_id = $resultSet->getIntegerValue("order_item_id");
        $this->promotion_id = $resultSet->getIntegerValue("promotion_id");
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
        $order_item_id = Escaper::quoteInt($this->order_item_id);
        $promotion_id = Escaper::quoteInt($this->promotion_id);
        $connection->execute("CALL sylius_promotion_order_item_DB_PK($order_item_id,$promotion_id)");
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
        $this->setOrder_item_id($arrayAccessor->getIntegerValue("order_item_id"));
        $this->setPromotion_id($arrayAccessor->getIntegerValue("promotion_id"));
        $this->_existing = ($this->order_item_id !== null) && ($this->promotion_id !== null);
        $49838ED1139DF194Array = $arrayAccessor->getArray("49838ED1139DF194");
        if ($49838ED1139DF194Array !== null) {
            $49838ED1139DF194 = sylius_promotionService::getInstance()->newInstance();
            $49838ED1139DF194->fromArray($49838ED1139DF194Array);
            $this->set49838ED1139DF194($49838ED1139DF194);
        }
        $49838ED1E415FB15Array = $arrayAccessor->getArray("49838ED1E415FB15");
        if ($49838ED1E415FB15Array !== null) {
            $49838ED1E415FB15 = sylius_order_itemService::getInstance()->newInstance();
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
            "order_item_id" => $this->getOrder_item_id(),
            "promotion_id" => $this->getPromotion_id()
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
    public function getOrder_item_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->order_item_id === null) {
            $this->order_item_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->order_item_id;
    }

    /**
     * @param int $order_item_id
     * 
     * @return void
     */
    public function setOrder_item_id(int $order_item_id = null)
    {
        $this->order_item_id = $order_item_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getPromotion_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->promotion_id === null) {
            $this->promotion_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->promotion_id;
    }

    /**
     * @param int $promotion_id
     * 
     * @return void
     */
    public function setPromotion_id(int $promotion_id = null)
    {
        $this->promotion_id = $promotion_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_promotion|null
     */
    public function get49838ED1139DF194(bool $forceReload = false)
    {
        if ($this->49838ED1139DF194 === null || $forceReload) {
            $this->49838ED1139DF194 = sylius_promotionService::getInstance()->getEntityByPrimaryKey($this->promotion_id);
        }
        return $this->49838ED1139DF194;
    }

    /**
     * @param sylius_promotion $entity
     * 
     * @return void
     */
    public function set49838ED1139DF194(sylius_promotion $entity = null)
    {
        $this->49838ED1139DF194 = $entity;
        $this->promotion_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order_item|null
     */
    public function get49838ED1E415FB15(bool $forceReload = false)
    {
        if ($this->49838ED1E415FB15 === null || $forceReload) {
            $this->49838ED1E415FB15 = sylius_order_itemService::getInstance()->getEntityByPrimaryKey($this->order_item_id);
        }
        return $this->49838ED1E415FB15;
    }

    /**
     * @param sylius_order_item $entity
     * 
     * @return void
     */
    public function set49838ED1E415FB15(sylius_order_item $entity = null)
    {
        $this->49838ED1E415FB15 = $entity;
        $this->order_item_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_promotion_order_item $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_promotion_order_item $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getOrder_item_id() === $entity->getOrder_item_id() && $this->getPromotion_id() === $entity->getPromotion_id();
    }

}