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

class sylius_shipment implements ArraySerializable
{

    const TABLE_NAME = "sylius_shipment";

    const COLUMN_ID = "id";

    const COLUMN_METHOD_ID = "method_id";

    const COLUMN_ORDER_ID = "order_id";

    const COLUMN_STATE = "state";

    const COLUMN_TRACKING = "tracking";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

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
    protected $method_id;

    /**
     * @var int
     */
    protected $order_id;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $tracking;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_shipping_method
     */
    protected $FD707B3319883967;

    /**
     * @var sylius_order
     */
    protected $FD707B338D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_shipment_U(" : "CALL sylius_shipment_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->method_id) . ',' . Escaper::quoteInt($this->order_id) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteString($connection, $this->tracking) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        if ($cascade && $this->FD707B3319883967 !== null) {
            $this->FD707B3319883967->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->FD707B338D9F6D38 !== null) {
            $this->FD707B338D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->method_id = $resultSet->getIntegerValue("method_id");
        $this->order_id = $resultSet->getIntegerValue("order_id");
        $this->state = $resultSet->getStringValue("state");
        $this->tracking = $resultSet->getStringValue("tracking");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
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
        $connection->execute("CALL sylius_shipment_DB_PK($id)");
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
        $this->setMethod_id($arrayAccessor->getIntegerValue("method_id"));
        $this->setOrder_id($arrayAccessor->getIntegerValue("order_id"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setTracking($arrayAccessor->getStringValue("tracking"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $FD707B3319883967Array = $arrayAccessor->getArray("FD707B3319883967");
        if ($FD707B3319883967Array !== null) {
            $FD707B3319883967 = sylius_shipping_methodService::getInstance()->newInstance();
            $FD707B3319883967->fromArray($FD707B3319883967Array);
            $this->setFD707B3319883967($FD707B3319883967);
        }
        $FD707B338D9F6D38Array = $arrayAccessor->getArray("FD707B338D9F6D38");
        if ($FD707B338D9F6D38Array !== null) {
            $FD707B338D9F6D38 = sylius_orderService::getInstance()->newInstance();
            $FD707B338D9F6D38->fromArray($FD707B338D9F6D38Array);
            $this->setFD707B338D9F6D38($FD707B338D9F6D38);
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
            "method_id" => $this->getMethod_id(),
            "order_id" => $this->getOrder_id(),
            "state" => $this->getState(),
            "tracking" => $this->getTracking(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
        ];
        if ($this->FD707B3319883967 !== null) {
            $result["FD707B3319883967"] = $this->FD707B3319883967->toArray($cycleDetector);
        }
        if ($this->FD707B338D9F6D38 !== null) {
            $result["FD707B338D9F6D38"] = $this->FD707B338D9F6D38->toArray($cycleDetector);
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
    public function getMethod_id()
    {
        return $this->method_id;
    }

    /**
     * @param int $method_id
     * 
     * @return void
     */
    public function setMethod_id(int $method_id = null)
    {
        $this->method_id = $method_id;
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
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * 
     * @return void
     */
    public function setState(string $state = null)
    {
        $this->state = StringUtil::trimToNull($state, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getTracking()
    {
        return $this->tracking;
    }

    /**
     * @param string $tracking
     * 
     * @return void
     */
    public function setTracking(string $tracking = null)
    {
        $this->tracking = StringUtil::trimToNull($tracking, 255);
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
     * @param bool $forceReload
     * 
     * @return sylius_shipping_method|null
     */
    public function getFD707B3319883967(bool $forceReload = false)
    {
        if ($this->FD707B3319883967 === null || $forceReload) {
            $this->FD707B3319883967 = sylius_shipping_methodService::getInstance()->getEntityByPrimaryKey($this->method_id);
        }
        return $this->FD707B3319883967;
    }

    /**
     * @param sylius_shipping_method $entity
     * 
     * @return void
     */
    public function setFD707B3319883967(sylius_shipping_method $entity = null)
    {
        $this->FD707B3319883967 = $entity;
        $this->method_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order|null
     */
    public function getFD707B338D9F6D38(bool $forceReload = false)
    {
        if ($this->FD707B338D9F6D38 === null || $forceReload) {
            $this->FD707B338D9F6D38 = sylius_orderService::getInstance()->getEntityByPrimaryKey($this->order_id);
        }
        return $this->FD707B338D9F6D38;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return void
     */
    public function setFD707B338D9F6D38(sylius_order $entity = null)
    {
        $this->FD707B338D9F6D38 = $entity;
        $this->order_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_shipment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_shipment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}