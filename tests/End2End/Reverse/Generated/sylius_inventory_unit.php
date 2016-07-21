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
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class sylius_inventory_unit implements ArraySerializable
{

    const TABLE_NAME = "sylius_inventory_unit";

    const COLUMN_ID = "id";

    const COLUMN_STOCKABLE_ID = "stockable_id";

    const COLUMN_ORDER_ITEM_ID = "order_item_id";

    const COLUMN_SHIPMENT_ID = "shipment_id";

    const COLUMN_INVENTORY_STATE = "inventory_state";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_SHIPPING_STATE = "shipping_state";

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
    protected $stockable_id;

    /**
     * @var int
     */
    protected $order_item_id;

    /**
     * @var int
     */
    protected $shipment_id;

    /**
     * @var string
     */
    protected $inventory_state;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var string
     */
    protected $shipping_state;

    /**
     * @var sylius_shipment
     */
    protected $4A2769867BE036FC;

    /**
     * @var sylius_order_item
     */
    protected $4A276986E415FB15;

    /**
     * @var sylius_product_variant
     */
    protected $4A276986FBE8234;

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
        $spCall = ($this->_existing) ? "CALL sylius_inventory_unit_U(" : "CALL sylius_inventory_unit_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->stockable_id) . ',' . Escaper::quoteInt($this->order_item_id) . ',' . Escaper::quoteInt($this->shipment_id) . ',' . Escaper::quoteString($connection, $this->inventory_state) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteString($connection, $this->shipping_state) . ');';
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
        if ($cascade && $this->4A2769867BE036FC !== null) {
            $this->4A2769867BE036FC->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->4A276986E415FB15 !== null) {
            $this->4A276986E415FB15->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->4A276986FBE8234 !== null) {
            $this->4A276986FBE8234->save($cascade, $cycleDetector, $connectionName);
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
        $this->stockable_id = $resultSet->getIntegerValue("stockable_id");
        $this->order_item_id = $resultSet->getIntegerValue("order_item_id");
        $this->shipment_id = $resultSet->getIntegerValue("shipment_id");
        $this->inventory_state = $resultSet->getStringValue("inventory_state");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->shipping_state = $resultSet->getStringValue("shipping_state");
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
        $connection->execute("CALL sylius_inventory_unit_DB_PK($id)");
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
        $this->setStockable_id($arrayAccessor->getIntegerValue("stockable_id"));
        $this->setOrder_item_id($arrayAccessor->getIntegerValue("order_item_id"));
        $this->setShipment_id($arrayAccessor->getIntegerValue("shipment_id"));
        $this->setInventory_state($arrayAccessor->getStringValue("inventory_state"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setShipping_state($arrayAccessor->getStringValue("shipping_state"));
        $this->_existing = ($this->id !== null);
        $4A2769867BE036FCArray = $arrayAccessor->getArray("4A2769867BE036FC");
        if ($4A2769867BE036FCArray !== null) {
            $4A2769867BE036FC = sylius_shipmentService::getInstance()->newInstance();
            $4A2769867BE036FC->fromArray($4A2769867BE036FCArray);
            $this->set4A2769867BE036FC($4A2769867BE036FC);
        }
        $4A276986E415FB15Array = $arrayAccessor->getArray("4A276986E415FB15");
        if ($4A276986E415FB15Array !== null) {
            $4A276986E415FB15 = sylius_order_itemService::getInstance()->newInstance();
            $4A276986E415FB15->fromArray($4A276986E415FB15Array);
            $this->set4A276986E415FB15($4A276986E415FB15);
        }
        $4A276986FBE8234Array = $arrayAccessor->getArray("4A276986FBE8234");
        if ($4A276986FBE8234Array !== null) {
            $4A276986FBE8234 = sylius_product_variantService::getInstance()->newInstance();
            $4A276986FBE8234->fromArray($4A276986FBE8234Array);
            $this->set4A276986FBE8234($4A276986FBE8234);
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
            "stockable_id" => $this->getStockable_id(),
            "order_item_id" => $this->getOrder_item_id(),
            "shipment_id" => $this->getShipment_id(),
            "inventory_state" => $this->getInventory_state(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "shipping_state" => $this->getShipping_state()
        ];
        if ($this->4A2769867BE036FC !== null) {
            $result["4A2769867BE036FC"] = $this->4A2769867BE036FC->toArray($cycleDetector);
        }
        if ($this->4A276986E415FB15 !== null) {
            $result["4A276986E415FB15"] = $this->4A276986E415FB15->toArray($cycleDetector);
        }
        if ($this->4A276986FBE8234 !== null) {
            $result["4A276986FBE8234"] = $this->4A276986FBE8234->toArray($cycleDetector);
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
    public function getStockable_id()
    {
        return $this->stockable_id;
    }

    /**
     * @param int $stockable_id
     * 
     * @return void
     */
    public function setStockable_id(int $stockable_id = null)
    {
        $this->stockable_id = $stockable_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getOrder_item_id()
    {
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
     * 
     * @return int|null
     */
    public function getShipment_id()
    {
        return $this->shipment_id;
    }

    /**
     * @param int $shipment_id
     * 
     * @return void
     */
    public function setShipment_id(int $shipment_id = null)
    {
        $this->shipment_id = $shipment_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getInventory_state()
    {
        return $this->inventory_state;
    }

    /**
     * @param string $inventory_state
     * 
     * @return void
     */
    public function setInventory_state(string $inventory_state = null)
    {
        $this->inventory_state = StringUtil::trimToNull($inventory_state, 255);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * @param SiestaDateTime $created_at
     * 
     * @return void
     */
    public function setCreated_at(SiestaDateTime $created_at = null)
    {
        $this->created_at = $created_at;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * @param SiestaDateTime $updated_at
     * 
     * @return void
     */
    public function setUpdated_at(SiestaDateTime $updated_at = null)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 
     * @return string|null
     */
    public function getShipping_state()
    {
        return $this->shipping_state;
    }

    /**
     * @param string $shipping_state
     * 
     * @return void
     */
    public function setShipping_state(string $shipping_state = null)
    {
        $this->shipping_state = StringUtil::trimToNull($shipping_state, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_shipment|null
     */
    public function get4A2769867BE036FC(bool $forceReload = false)
    {
        if ($this->4A2769867BE036FC === null || $forceReload) {
            $this->4A2769867BE036FC = sylius_shipmentService::getInstance()->getEntityByPrimaryKey($this->shipment_id);
        }
        return $this->4A2769867BE036FC;
    }

    /**
     * @param sylius_shipment $entity
     * 
     * @return void
     */
    public function set4A2769867BE036FC(sylius_shipment $entity = null)
    {
        $this->4A2769867BE036FC = $entity;
        $this->shipment_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order_item|null
     */
    public function get4A276986E415FB15(bool $forceReload = false)
    {
        if ($this->4A276986E415FB15 === null || $forceReload) {
            $this->4A276986E415FB15 = sylius_order_itemService::getInstance()->getEntityByPrimaryKey($this->order_item_id);
        }
        return $this->4A276986E415FB15;
    }

    /**
     * @param sylius_order_item $entity
     * 
     * @return void
     */
    public function set4A276986E415FB15(sylius_order_item $entity = null)
    {
        $this->4A276986E415FB15 = $entity;
        $this->order_item_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_variant|null
     */
    public function get4A276986FBE8234(bool $forceReload = false)
    {
        if ($this->4A276986FBE8234 === null || $forceReload) {
            $this->4A276986FBE8234 = sylius_product_variantService::getInstance()->getEntityByPrimaryKey($this->stockable_id);
        }
        return $this->4A276986FBE8234;
    }

    /**
     * @param sylius_product_variant $entity
     * 
     * @return void
     */
    public function set4A276986FBE8234(sylius_product_variant $entity = null)
    {
        $this->4A276986FBE8234 = $entity;
        $this->stockable_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_inventory_unit $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_inventory_unit $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}