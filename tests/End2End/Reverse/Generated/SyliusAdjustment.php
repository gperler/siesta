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

class SyliusAdjustment implements ArraySerializable
{

    const TABLE_NAME = "sylius_adjustment";

    const COLUMN_ID = "id";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_ORDERITEMID = "order_item_id";

    const COLUMN_TYPE = "type";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_AMOUNT = "amount";

    const COLUMN_ISNEUTRAL = "is_neutral";

    const COLUMN_ISLOCKED = "is_locked";

    const COLUMN_ORIGINID = "origin_id";

    const COLUMN_ORIGINTYPE = "origin_type";

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
    protected $orderId;

    /**
     * @var int
     */
    protected $orderItemId;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $isNeutral;

    /**
     * @var string
     */
    protected $isLocked;

    /**
     * @var int
     */
    protected $originId;

    /**
     * @var string
     */
    protected $originType;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusOrder
     */
    protected $ACA6E0F28D9F6D38;

    /**
     * @var SyliusOrderItem
     */
    protected $ACA6E0F2E415FB15;

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
        $spCall = ($this->_existing) ? "CALL sylius_adjustment_U(" : "CALL sylius_adjustment_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteInt($this->orderItemId) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteInt($this->amount) . ',' . Escaper::quoteString($connection, $this->isNeutral) . ',' . Escaper::quoteString($connection, $this->isLocked) . ',' . Escaper::quoteInt($this->originId) . ',' . Escaper::quoteString($connection, $this->originType) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        if ($cascade && $this->ACA6E0F28D9F6D38 !== null) {
            $this->ACA6E0F28D9F6D38->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->ACA6E0F2E415FB15 !== null) {
            $this->ACA6E0F2E415FB15->save($cascade, $cycleDetector, $connectionName);
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
        $this->orderId = $resultSet->getIntegerValue("order_id");
        $this->orderItemId = $resultSet->getIntegerValue("order_item_id");
        $this->type = $resultSet->getStringValue("type");
        $this->description = $resultSet->getStringValue("description");
        $this->amount = $resultSet->getIntegerValue("amount");
        $this->isNeutral = $resultSet->getStringValue("is_neutral");
        $this->isLocked = $resultSet->getStringValue("is_locked");
        $this->originId = $resultSet->getIntegerValue("origin_id");
        $this->originType = $resultSet->getStringValue("origin_type");
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
        $connection->execute("CALL sylius_adjustment_DB_PK($id)");
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
        $this->setOrderId($arrayAccessor->getIntegerValue("orderId"));
        $this->setOrderItemId($arrayAccessor->getIntegerValue("orderItemId"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setAmount($arrayAccessor->getIntegerValue("amount"));
        $this->setIsNeutral($arrayAccessor->getStringValue("isNeutral"));
        $this->setIsLocked($arrayAccessor->getStringValue("isLocked"));
        $this->setOriginId($arrayAccessor->getIntegerValue("originId"));
        $this->setOriginType($arrayAccessor->getStringValue("originType"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $ACA6E0F28D9F6D38Array = $arrayAccessor->getArray("ACA6E0F28D9F6D38");
        if ($ACA6E0F28D9F6D38Array !== null) {
            $ACA6E0F28D9F6D38 = SyliusOrderService::getInstance()->newInstance();
            $ACA6E0F28D9F6D38->fromArray($ACA6E0F28D9F6D38Array);
            $this->setACA6E0F28D9F6D38($ACA6E0F28D9F6D38);
        }
        $ACA6E0F2E415FB15Array = $arrayAccessor->getArray("ACA6E0F2E415FB15");
        if ($ACA6E0F2E415FB15Array !== null) {
            $ACA6E0F2E415FB15 = SyliusOrderItemService::getInstance()->newInstance();
            $ACA6E0F2E415FB15->fromArray($ACA6E0F2E415FB15Array);
            $this->setACA6E0F2E415FB15($ACA6E0F2E415FB15);
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
            "orderId" => $this->getOrderId(),
            "orderItemId" => $this->getOrderItemId(),
            "type" => $this->getType(),
            "description" => $this->getDescription(),
            "amount" => $this->getAmount(),
            "isNeutral" => $this->getIsNeutral(),
            "isLocked" => $this->getIsLocked(),
            "originId" => $this->getOriginId(),
            "originType" => $this->getOriginType(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
        ];
        if ($this->ACA6E0F28D9F6D38 !== null) {
            $result["ACA6E0F28D9F6D38"] = $this->ACA6E0F28D9F6D38->toArray($cycleDetector);
        }
        if ($this->ACA6E0F2E415FB15 !== null) {
            $result["ACA6E0F2E415FB15"] = $this->ACA6E0F2E415FB15->toArray($cycleDetector);
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
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type = null)
    {
        $this->type = StringUtil::trimToNull($type, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return void
     */
    public function setDescription(string $description = null)
    {
        $this->description = StringUtil::trimToNull($description, 255);
    }

    /**
     * 
     * @return int|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * 
     * @return void
     */
    public function setAmount(int $amount = null)
    {
        $this->amount = $amount;
    }

    /**
     * 
     * @return string|null
     */
    public function getIsNeutral()
    {
        return $this->isNeutral;
    }

    /**
     * @param string $isNeutral
     * 
     * @return void
     */
    public function setIsNeutral(string $isNeutral = null)
    {
        $this->isNeutral = StringUtil::trimToNull($isNeutral, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * @param string $isLocked
     * 
     * @return void
     */
    public function setIsLocked(string $isLocked = null)
    {
        $this->isLocked = StringUtil::trimToNull($isLocked, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getOriginId()
    {
        return $this->originId;
    }

    /**
     * @param int $originId
     * 
     * @return void
     */
    public function setOriginId(int $originId = null)
    {
        $this->originId = $originId;
    }

    /**
     * 
     * @return string|null
     */
    public function getOriginType()
    {
        return $this->originType;
    }

    /**
     * @param string $originType
     * 
     * @return void
     */
    public function setOriginType(string $originType = null)
    {
        $this->originType = StringUtil::trimToNull($originType, 255);
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
     * @return SyliusOrder|null
     */
    public function getACA6E0F28D9F6D38(bool $forceReload = false)
    {
        if ($this->ACA6E0F28D9F6D38 === null || $forceReload) {
            $this->ACA6E0F28D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->ACA6E0F28D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function setACA6E0F28D9F6D38(SyliusOrder $entity = null)
    {
        $this->ACA6E0F28D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrderItem|null
     */
    public function getACA6E0F2E415FB15(bool $forceReload = false)
    {
        if ($this->ACA6E0F2E415FB15 === null || $forceReload) {
            $this->ACA6E0F2E415FB15 = SyliusOrderItemService::getInstance()->getEntityByPrimaryKey($this->orderItemId);
        }
        return $this->ACA6E0F2E415FB15;
    }

    /**
     * @param SyliusOrderItem $entity
     * 
     * @return void
     */
    public function setACA6E0F2E415FB15(SyliusOrderItem $entity = null)
    {
        $this->ACA6E0F2E415FB15 = $entity;
        $this->orderItemId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusAdjustment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusAdjustment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}