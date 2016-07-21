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

class SyliusShipment implements ArraySerializable
{

    const TABLE_NAME = "sylius_shipment";

    const COLUMN_ID = "id";

    const COLUMN_METHODID = "method_id";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_STATE = "state";

    const COLUMN_TRACKING = "tracking";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

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
    protected $methodId;

    /**
     * @var int
     */
    protected $orderId;

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
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusShippingMethod
     */
    protected $FD707B3319883967;

    /**
     * @var SyliusOrder
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->methodId) . ',' . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteString($connection, $this->tracking) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        $this->methodId = $resultSet->getIntegerValue("method_id");
        $this->orderId = $resultSet->getIntegerValue("order_id");
        $this->state = $resultSet->getStringValue("state");
        $this->tracking = $resultSet->getStringValue("tracking");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
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
        $this->setMethodId($arrayAccessor->getIntegerValue("methodId"));
        $this->setOrderId($arrayAccessor->getIntegerValue("orderId"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setTracking($arrayAccessor->getStringValue("tracking"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $FD707B3319883967Array = $arrayAccessor->getArray("FD707B3319883967");
        if ($FD707B3319883967Array !== null) {
            $FD707B3319883967 = SyliusShippingMethodService::getInstance()->newInstance();
            $FD707B3319883967->fromArray($FD707B3319883967Array);
            $this->setFD707B3319883967($FD707B3319883967);
        }
        $FD707B338D9F6D38Array = $arrayAccessor->getArray("FD707B338D9F6D38");
        if ($FD707B338D9F6D38Array !== null) {
            $FD707B338D9F6D38 = SyliusOrderService::getInstance()->newInstance();
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
            "methodId" => $this->getMethodId(),
            "orderId" => $this->getOrderId(),
            "state" => $this->getState(),
            "tracking" => $this->getTracking(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
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
    public function getMethodId()
    {
        return $this->methodId;
    }

    /**
     * @param int $methodId
     * 
     * @return void
     */
    public function setMethodId(int $methodId = null)
    {
        $this->methodId = $methodId;
    }

    /**
     * 
     * @return int|null
     */
    public function getOrderId()
    {
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
     * @param bool $forceReload
     * 
     * @return SyliusShippingMethod|null
     */
    public function getFD707B3319883967(bool $forceReload = false)
    {
        if ($this->FD707B3319883967 === null || $forceReload) {
            $this->FD707B3319883967 = SyliusShippingMethodService::getInstance()->getEntityByPrimaryKey($this->methodId);
        }
        return $this->FD707B3319883967;
    }

    /**
     * @param SyliusShippingMethod $entity
     * 
     * @return void
     */
    public function setFD707B3319883967(SyliusShippingMethod $entity = null)
    {
        $this->FD707B3319883967 = $entity;
        $this->methodId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrder|null
     */
    public function getFD707B338D9F6D38(bool $forceReload = false)
    {
        if ($this->FD707B338D9F6D38 === null || $forceReload) {
            $this->FD707B338D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->FD707B338D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function setFD707B338D9F6D38(SyliusOrder $entity = null)
    {
        $this->FD707B338D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusShipment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusShipment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}