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

class sylius_adjustment implements ArraySerializable
{

    const TABLE_NAME = "sylius_adjustment";

    const COLUMN_ID = "id";

    const COLUMN_ORDER_ID = "order_id";

    const COLUMN_ORDER_ITEM_ID = "order_item_id";

    const COLUMN_TYPE = "type";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_AMOUNT = "amount";

    const COLUMN_IS_NEUTRAL = "is_neutral";

    const COLUMN_IS_LOCKED = "is_locked";

    const COLUMN_ORIGIN_ID = "origin_id";

    const COLUMN_ORIGIN_TYPE = "origin_type";

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
    protected $order_id;

    /**
     * @var int
     */
    protected $order_item_id;

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
    protected $is_neutral;

    /**
     * @var string
     */
    protected $is_locked;

    /**
     * @var int
     */
    protected $origin_id;

    /**
     * @var string
     */
    protected $origin_type;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_order
     */
    protected $ACA6E0F28D9F6D38;

    /**
     * @var sylius_order_item
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->order_id) . ',' . Escaper::quoteInt($this->order_item_id) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteInt($this->amount) . ',' . Escaper::quoteString($connection, $this->is_neutral) . ',' . Escaper::quoteString($connection, $this->is_locked) . ',' . Escaper::quoteInt($this->origin_id) . ',' . Escaper::quoteString($connection, $this->origin_type) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        $this->order_id = $resultSet->getIntegerValue("order_id");
        $this->order_item_id = $resultSet->getIntegerValue("order_item_id");
        $this->type = $resultSet->getStringValue("type");
        $this->description = $resultSet->getStringValue("description");
        $this->amount = $resultSet->getIntegerValue("amount");
        $this->is_neutral = $resultSet->getStringValue("is_neutral");
        $this->is_locked = $resultSet->getStringValue("is_locked");
        $this->origin_id = $resultSet->getIntegerValue("origin_id");
        $this->origin_type = $resultSet->getStringValue("origin_type");
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
        $this->setOrder_id($arrayAccessor->getIntegerValue("order_id"));
        $this->setOrder_item_id($arrayAccessor->getIntegerValue("order_item_id"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setAmount($arrayAccessor->getIntegerValue("amount"));
        $this->setIs_neutral($arrayAccessor->getStringValue("is_neutral"));
        $this->setIs_locked($arrayAccessor->getStringValue("is_locked"));
        $this->setOrigin_id($arrayAccessor->getIntegerValue("origin_id"));
        $this->setOrigin_type($arrayAccessor->getStringValue("origin_type"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $ACA6E0F28D9F6D38Array = $arrayAccessor->getArray("ACA6E0F28D9F6D38");
        if ($ACA6E0F28D9F6D38Array !== null) {
            $ACA6E0F28D9F6D38 = sylius_orderService::getInstance()->newInstance();
            $ACA6E0F28D9F6D38->fromArray($ACA6E0F28D9F6D38Array);
            $this->setACA6E0F28D9F6D38($ACA6E0F28D9F6D38);
        }
        $ACA6E0F2E415FB15Array = $arrayAccessor->getArray("ACA6E0F2E415FB15");
        if ($ACA6E0F2E415FB15Array !== null) {
            $ACA6E0F2E415FB15 = sylius_order_itemService::getInstance()->newInstance();
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
            "order_id" => $this->getOrder_id(),
            "order_item_id" => $this->getOrder_item_id(),
            "type" => $this->getType(),
            "description" => $this->getDescription(),
            "amount" => $this->getAmount(),
            "is_neutral" => $this->getIs_neutral(),
            "is_locked" => $this->getIs_locked(),
            "origin_id" => $this->getOrigin_id(),
            "origin_type" => $this->getOrigin_type(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
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
    public function getIs_neutral()
    {
        return $this->is_neutral;
    }

    /**
     * @param string $is_neutral
     * 
     * @return void
     */
    public function setIs_neutral(string $is_neutral = null)
    {
        $this->is_neutral = StringUtil::trimToNull($is_neutral, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getIs_locked()
    {
        return $this->is_locked;
    }

    /**
     * @param string $is_locked
     * 
     * @return void
     */
    public function setIs_locked(string $is_locked = null)
    {
        $this->is_locked = StringUtil::trimToNull($is_locked, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getOrigin_id()
    {
        return $this->origin_id;
    }

    /**
     * @param int $origin_id
     * 
     * @return void
     */
    public function setOrigin_id(int $origin_id = null)
    {
        $this->origin_id = $origin_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getOrigin_type()
    {
        return $this->origin_type;
    }

    /**
     * @param string $origin_type
     * 
     * @return void
     */
    public function setOrigin_type(string $origin_type = null)
    {
        $this->origin_type = StringUtil::trimToNull($origin_type, 255);
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
     * @return sylius_order|null
     */
    public function getACA6E0F28D9F6D38(bool $forceReload = false)
    {
        if ($this->ACA6E0F28D9F6D38 === null || $forceReload) {
            $this->ACA6E0F28D9F6D38 = sylius_orderService::getInstance()->getEntityByPrimaryKey($this->order_id);
        }
        return $this->ACA6E0F28D9F6D38;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return void
     */
    public function setACA6E0F28D9F6D38(sylius_order $entity = null)
    {
        $this->ACA6E0F28D9F6D38 = $entity;
        $this->order_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order_item|null
     */
    public function getACA6E0F2E415FB15(bool $forceReload = false)
    {
        if ($this->ACA6E0F2E415FB15 === null || $forceReload) {
            $this->ACA6E0F2E415FB15 = sylius_order_itemService::getInstance()->getEntityByPrimaryKey($this->order_item_id);
        }
        return $this->ACA6E0F2E415FB15;
    }

    /**
     * @param sylius_order_item $entity
     * 
     * @return void
     */
    public function setACA6E0F2E415FB15(sylius_order_item $entity = null)
    {
        $this->ACA6E0F2E415FB15 = $entity;
        $this->order_item_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_adjustment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_adjustment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}