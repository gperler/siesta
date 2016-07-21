<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\StringUtil;

class sylius_order_item implements ArraySerializable
{

    const TABLE_NAME = "sylius_order_item";

    const COLUMN_ID = "id";

    const COLUMN_ORDER_ID = "order_id";

    const COLUMN_VARIANT_ID = "variant_id";

    const COLUMN_QUANTITY = "quantity";

    const COLUMN_UNIT_PRICE = "unit_price";

    const COLUMN_ADJUSTMENTS_TOTAL = "adjustments_total";

    const COLUMN_TOTAL = "total";

    const COLUMN_IS_IMMUTABLE = "is_immutable";

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
    protected $id;

    /**
     * @var int
     */
    protected $order_id;

    /**
     * @var int
     */
    protected $variant_id;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $unit_price;

    /**
     * @var int
     */
    protected $adjustments_total;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var string
     */
    protected $is_immutable;

    /**
     * @var sylius_product_variant
     */
    protected $77B587ED3B69A9AF;

    /**
     * @var sylius_order
     */
    protected $77B587ED8D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_order_item_U(" : "CALL sylius_order_item_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->order_id) . ',' . Escaper::quoteInt($this->variant_id) . ',' . Escaper::quoteInt($this->quantity) . ',' . Escaper::quoteInt($this->unit_price) . ',' . Escaper::quoteInt($this->adjustments_total) . ',' . Escaper::quoteInt($this->total) . ',' . Escaper::quoteString($connection, $this->is_immutable) . ');';
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
        if ($cascade && $this->77B587ED3B69A9AF !== null) {
            $this->77B587ED3B69A9AF->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->77B587ED8D9F6D38 !== null) {
            $this->77B587ED8D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->id = $resultSet->getIntegerValue("id");
        $this->order_id = $resultSet->getIntegerValue("order_id");
        $this->variant_id = $resultSet->getIntegerValue("variant_id");
        $this->quantity = $resultSet->getIntegerValue("quantity");
        $this->unit_price = $resultSet->getIntegerValue("unit_price");
        $this->adjustments_total = $resultSet->getIntegerValue("adjustments_total");
        $this->total = $resultSet->getIntegerValue("total");
        $this->is_immutable = $resultSet->getStringValue("is_immutable");
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
        $id = Escaper::quoteInt($this->id);
        $connection->execute("CALL sylius_order_item_DB_PK($id)");
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
        $this->setId($arrayAccessor->getIntegerValue("id"));
        $this->setOrder_id($arrayAccessor->getIntegerValue("order_id"));
        $this->setVariant_id($arrayAccessor->getIntegerValue("variant_id"));
        $this->setQuantity($arrayAccessor->getIntegerValue("quantity"));
        $this->setUnit_price($arrayAccessor->getIntegerValue("unit_price"));
        $this->setAdjustments_total($arrayAccessor->getIntegerValue("adjustments_total"));
        $this->setTotal($arrayAccessor->getIntegerValue("total"));
        $this->setIs_immutable($arrayAccessor->getStringValue("is_immutable"));
        $this->_existing = ($this->id !== null);
        $77B587ED3B69A9AFArray = $arrayAccessor->getArray("77B587ED3B69A9AF");
        if ($77B587ED3B69A9AFArray !== null) {
            $77B587ED3B69A9AF = sylius_product_variantService::getInstance()->newInstance();
            $77B587ED3B69A9AF->fromArray($77B587ED3B69A9AFArray);
            $this->set77B587ED3B69A9AF($77B587ED3B69A9AF);
        }
        $77B587ED8D9F6D38Array = $arrayAccessor->getArray("77B587ED8D9F6D38");
        if ($77B587ED8D9F6D38Array !== null) {
            $77B587ED8D9F6D38 = sylius_orderService::getInstance()->newInstance();
            $77B587ED8D9F6D38->fromArray($77B587ED8D9F6D38Array);
            $this->set77B587ED8D9F6D38($77B587ED8D9F6D38);
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
            "id" => $this->getId(),
            "order_id" => $this->getOrder_id(),
            "variant_id" => $this->getVariant_id(),
            "quantity" => $this->getQuantity(),
            "unit_price" => $this->getUnit_price(),
            "adjustments_total" => $this->getAdjustments_total(),
            "total" => $this->getTotal(),
            "is_immutable" => $this->getIs_immutable()
        ];
        if ($this->77B587ED3B69A9AF !== null) {
            $result["77B587ED3B69A9AF"] = $this->77B587ED3B69A9AF->toArray($cycleDetector);
        }
        if ($this->77B587ED8D9F6D38 !== null) {
            $result["77B587ED8D9F6D38"] = $this->77B587ED8D9F6D38->toArray($cycleDetector);
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
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return int|null
     */
    public function getOrder_id()
    {
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
     * 
     * @return int|null
     */
    public function getVariant_id()
    {
        return $this->variant_id;
    }

    /**
     * @param int $variant_id
     * 
     * @return void
     */
    public function setVariant_id(int $variant_id = null)
    {
        $this->variant_id = $variant_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * 
     * @return void
     */
    public function setQuantity(int $quantity = null)
    {
        $this->quantity = $quantity;
    }

    /**
     * 
     * @return int|null
     */
    public function getUnit_price()
    {
        return $this->unit_price;
    }

    /**
     * @param int $unit_price
     * 
     * @return void
     */
    public function setUnit_price(int $unit_price = null)
    {
        $this->unit_price = $unit_price;
    }

    /**
     * 
     * @return int|null
     */
    public function getAdjustments_total()
    {
        return $this->adjustments_total;
    }

    /**
     * @param int $adjustments_total
     * 
     * @return void
     */
    public function setAdjustments_total(int $adjustments_total = null)
    {
        $this->adjustments_total = $adjustments_total;
    }

    /**
     * 
     * @return int|null
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     * 
     * @return void
     */
    public function setTotal(int $total = null)
    {
        $this->total = $total;
    }

    /**
     * 
     * @return string|null
     */
    public function getIs_immutable()
    {
        return $this->is_immutable;
    }

    /**
     * @param string $is_immutable
     * 
     * @return void
     */
    public function setIs_immutable(string $is_immutable = null)
    {
        $this->is_immutable = StringUtil::trimToNull($is_immutable, null);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_variant|null
     */
    public function get77B587ED3B69A9AF(bool $forceReload = false)
    {
        if ($this->77B587ED3B69A9AF === null || $forceReload) {
            $this->77B587ED3B69A9AF = sylius_product_variantService::getInstance()->getEntityByPrimaryKey($this->variant_id);
        }
        return $this->77B587ED3B69A9AF;
    }

    /**
     * @param sylius_product_variant $entity
     * 
     * @return void
     */
    public function set77B587ED3B69A9AF(sylius_product_variant $entity = null)
    {
        $this->77B587ED3B69A9AF = $entity;
        $this->variant_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order|null
     */
    public function get77B587ED8D9F6D38(bool $forceReload = false)
    {
        if ($this->77B587ED8D9F6D38 === null || $forceReload) {
            $this->77B587ED8D9F6D38 = sylius_orderService::getInstance()->getEntityByPrimaryKey($this->order_id);
        }
        return $this->77B587ED8D9F6D38;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return void
     */
    public function set77B587ED8D9F6D38(sylius_order $entity = null)
    {
        $this->77B587ED8D9F6D38 = $entity;
        $this->order_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_order_item $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_order_item $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}