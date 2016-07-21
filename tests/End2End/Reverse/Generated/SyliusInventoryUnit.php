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

class SyliusInventoryUnit implements ArraySerializable
{

    const TABLE_NAME = "sylius_inventory_unit";

    const COLUMN_ID = "id";

    const COLUMN_STOCKABLEID = "stockable_id";

    const COLUMN_ORDERITEMID = "order_item_id";

    const COLUMN_SHIPMENTID = "shipment_id";

    const COLUMN_INVENTORYSTATE = "inventory_state";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_SHIPPINGSTATE = "shipping_state";

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
    protected $stockableId;

    /**
     * @var int
     */
    protected $orderItemId;

    /**
     * @var int
     */
    protected $shipmentId;

    /**
     * @var string
     */
    protected $inventoryState;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $shippingState;

    /**
     * @var SyliusShipment
     */
    protected $4A2769867BE036FC;

    /**
     * @var SyliusOrderItem
     */
    protected $4A276986E415FB15;

    /**
     * @var SyliusProductVariant
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->stockableId) . ',' . Escaper::quoteInt($this->orderItemId) . ',' . Escaper::quoteInt($this->shipmentId) . ',' . Escaper::quoteString($connection, $this->inventoryState) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteString($connection, $this->shippingState) . ');';
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
        $this->stockableId = $resultSet->getIntegerValue("stockable_id");
        $this->orderItemId = $resultSet->getIntegerValue("order_item_id");
        $this->shipmentId = $resultSet->getIntegerValue("shipment_id");
        $this->inventoryState = $resultSet->getStringValue("inventory_state");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->shippingState = $resultSet->getStringValue("shipping_state");
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
        $this->setStockableId($arrayAccessor->getIntegerValue("stockableId"));
        $this->setOrderItemId($arrayAccessor->getIntegerValue("orderItemId"));
        $this->setShipmentId($arrayAccessor->getIntegerValue("shipmentId"));
        $this->setInventoryState($arrayAccessor->getStringValue("inventoryState"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setShippingState($arrayAccessor->getStringValue("shippingState"));
        $this->_existing = ($this->id !== null);
        $4A2769867BE036FCArray = $arrayAccessor->getArray("4A2769867BE036FC");
        if ($4A2769867BE036FCArray !== null) {
            $4A2769867BE036FC = SyliusShipmentService::getInstance()->newInstance();
            $4A2769867BE036FC->fromArray($4A2769867BE036FCArray);
            $this->set4A2769867BE036FC($4A2769867BE036FC);
        }
        $4A276986E415FB15Array = $arrayAccessor->getArray("4A276986E415FB15");
        if ($4A276986E415FB15Array !== null) {
            $4A276986E415FB15 = SyliusOrderItemService::getInstance()->newInstance();
            $4A276986E415FB15->fromArray($4A276986E415FB15Array);
            $this->set4A276986E415FB15($4A276986E415FB15);
        }
        $4A276986FBE8234Array = $arrayAccessor->getArray("4A276986FBE8234");
        if ($4A276986FBE8234Array !== null) {
            $4A276986FBE8234 = SyliusProductVariantService::getInstance()->newInstance();
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
            "stockableId" => $this->getStockableId(),
            "orderItemId" => $this->getOrderItemId(),
            "shipmentId" => $this->getShipmentId(),
            "inventoryState" => $this->getInventoryState(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "shippingState" => $this->getShippingState()
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
    public function getStockableId()
    {
        return $this->stockableId;
    }

    /**
     * @param int $stockableId
     * 
     * @return void
     */
    public function setStockableId(int $stockableId = null)
    {
        $this->stockableId = $stockableId;
    }

    /**
     * 
     * @return int|null
     */
    public function getOrderItemId()
    {
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
     * 
     * @return int|null
     */
    public function getShipmentId()
    {
        return $this->shipmentId;
    }

    /**
     * @param int $shipmentId
     * 
     * @return void
     */
    public function setShipmentId(int $shipmentId = null)
    {
        $this->shipmentId = $shipmentId;
    }

    /**
     * 
     * @return string|null
     */
    public function getInventoryState()
    {
        return $this->inventoryState;
    }

    /**
     * @param string $inventoryState
     * 
     * @return void
     */
    public function setInventoryState(string $inventoryState = null)
    {
        $this->inventoryState = StringUtil::trimToNull($inventoryState, 255);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param SiestaDateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(SiestaDateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param SiestaDateTime $updatedAt
     * 
     * @return void
     */
    public function setUpdatedAt(SiestaDateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 
     * @return string|null
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * @param string $shippingState
     * 
     * @return void
     */
    public function setShippingState(string $shippingState = null)
    {
        $this->shippingState = StringUtil::trimToNull($shippingState, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusShipment|null
     */
    public function get4A2769867BE036FC(bool $forceReload = false)
    {
        if ($this->4A2769867BE036FC === null || $forceReload) {
            $this->4A2769867BE036FC = SyliusShipmentService::getInstance()->getEntityByPrimaryKey($this->shipmentId);
        }
        return $this->4A2769867BE036FC;
    }

    /**
     * @param SyliusShipment $entity
     * 
     * @return void
     */
    public function set4A2769867BE036FC(SyliusShipment $entity = null)
    {
        $this->4A2769867BE036FC = $entity;
        $this->shipmentId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrderItem|null
     */
    public function get4A276986E415FB15(bool $forceReload = false)
    {
        if ($this->4A276986E415FB15 === null || $forceReload) {
            $this->4A276986E415FB15 = SyliusOrderItemService::getInstance()->getEntityByPrimaryKey($this->orderItemId);
        }
        return $this->4A276986E415FB15;
    }

    /**
     * @param SyliusOrderItem $entity
     * 
     * @return void
     */
    public function set4A276986E415FB15(SyliusOrderItem $entity = null)
    {
        $this->4A276986E415FB15 = $entity;
        $this->orderItemId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductVariant|null
     */
    public function get4A276986FBE8234(bool $forceReload = false)
    {
        if ($this->4A276986FBE8234 === null || $forceReload) {
            $this->4A276986FBE8234 = SyliusProductVariantService::getInstance()->getEntityByPrimaryKey($this->stockableId);
        }
        return $this->4A276986FBE8234;
    }

    /**
     * @param SyliusProductVariant $entity
     * 
     * @return void
     */
    public function set4A276986FBE8234(SyliusProductVariant $entity = null)
    {
        $this->4A276986FBE8234 = $entity;
        $this->stockableId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusInventoryUnit $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusInventoryUnit $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}