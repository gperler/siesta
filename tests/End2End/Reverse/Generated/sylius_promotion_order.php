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

class sylius_promotion_order implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_order";

    const COLUMN_ORDER_ID = "order_id";

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
    protected $order_id;

    /**
     * @var int
     */
    protected $promotion_id;

    /**
     * @var sylius_promotion
     */
    protected $BF9CF6FB139DF194;

    /**
     * @var sylius_order
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
        $this->getOrder_id(true, $connectionName);
        $this->getPromotion_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->order_id) . ',' . Escaper::quoteInt($this->promotion_id) . ');';
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
        $this->order_id = $resultSet->getIntegerValue("order_id");
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
        $order_id = Escaper::quoteInt($this->order_id);
        $promotion_id = Escaper::quoteInt($this->promotion_id);
        $connection->execute("CALL sylius_promotion_order_DB_PK($order_id,$promotion_id)");
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
        $this->setOrder_id($arrayAccessor->getIntegerValue("order_id"));
        $this->setPromotion_id($arrayAccessor->getIntegerValue("promotion_id"));
        $this->_existing = ($this->order_id !== null) && ($this->promotion_id !== null);
        $BF9CF6FB139DF194Array = $arrayAccessor->getArray("BF9CF6FB139DF194");
        if ($BF9CF6FB139DF194Array !== null) {
            $BF9CF6FB139DF194 = sylius_promotionService::getInstance()->newInstance();
            $BF9CF6FB139DF194->fromArray($BF9CF6FB139DF194Array);
            $this->setBF9CF6FB139DF194($BF9CF6FB139DF194);
        }
        $BF9CF6FB8D9F6D38Array = $arrayAccessor->getArray("BF9CF6FB8D9F6D38");
        if ($BF9CF6FB8D9F6D38Array !== null) {
            $BF9CF6FB8D9F6D38 = sylius_orderService::getInstance()->newInstance();
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
            "order_id" => $this->getOrder_id(),
            "promotion_id" => $this->getPromotion_id()
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
    public function getOrder_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->order_id === null) {
            $this->order_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->order_id;
    }

    /**
     * @param int $order_id
     * 
     * @return void
     */
    public function setOrder_id(int $order_id = null)
    {
        $this->order_id = $order_id;
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
    public function getBF9CF6FB139DF194(bool $forceReload = false)
    {
        if ($this->BF9CF6FB139DF194 === null || $forceReload) {
            $this->BF9CF6FB139DF194 = sylius_promotionService::getInstance()->getEntityByPrimaryKey($this->promotion_id);
        }
        return $this->BF9CF6FB139DF194;
    }

    /**
     * @param sylius_promotion $entity
     * 
     * @return void
     */
    public function setBF9CF6FB139DF194(sylius_promotion $entity = null)
    {
        $this->BF9CF6FB139DF194 = $entity;
        $this->promotion_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order|null
     */
    public function getBF9CF6FB8D9F6D38(bool $forceReload = false)
    {
        if ($this->BF9CF6FB8D9F6D38 === null || $forceReload) {
            $this->BF9CF6FB8D9F6D38 = sylius_orderService::getInstance()->getEntityByPrimaryKey($this->order_id);
        }
        return $this->BF9CF6FB8D9F6D38;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return void
     */
    public function setBF9CF6FB8D9F6D38(sylius_order $entity = null)
    {
        $this->BF9CF6FB8D9F6D38 = $entity;
        $this->order_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_promotion_order $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_promotion_order $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getOrder_id() === $entity->getOrder_id() && $this->getPromotion_id() === $entity->getPromotion_id();
    }

}