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

class sylius_payment implements ArraySerializable
{

    const TABLE_NAME = "sylius_payment";

    const COLUMN_ID = "id";

    const COLUMN_METHOD_ID = "method_id";

    const COLUMN_CREDIT_CARD_ID = "credit_card_id";

    const COLUMN_ORDER_ID = "order_id";

    const COLUMN_CURRENCY = "currency";

    const COLUMN_AMOUNT = "amount";

    const COLUMN_STATE = "state";

    const COLUMN_DETAILS = "details";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

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
    protected $credit_card_id;

    /**
     * @var int
     */
    protected $order_id;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $details;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var SiestaDateTime
     */
    protected $deleted_at;

    /**
     * @var sylius_payment_method
     */
    protected $D9191BD419883967;

    /**
     * @var sylius_credit_card
     */
    protected $D9191BD47048FD0F;

    /**
     * @var sylius_order
     */
    protected $D9191BD48D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_payment_U(" : "CALL sylius_payment_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->method_id) . ',' . Escaper::quoteInt($this->credit_card_id) . ',' . Escaper::quoteInt($this->order_id) . ',' . Escaper::quoteString($connection, $this->currency) . ',' . Escaper::quoteInt($this->amount) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteString($connection, $this->details) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ');';
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
        if ($cascade && $this->D9191BD419883967 !== null) {
            $this->D9191BD419883967->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->D9191BD47048FD0F !== null) {
            $this->D9191BD47048FD0F->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->D9191BD48D9F6D38 !== null) {
            $this->D9191BD48D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->credit_card_id = $resultSet->getIntegerValue("credit_card_id");
        $this->order_id = $resultSet->getIntegerValue("order_id");
        $this->currency = $resultSet->getStringValue("currency");
        $this->amount = $resultSet->getIntegerValue("amount");
        $this->state = $resultSet->getStringValue("state");
        $this->details = $resultSet->getStringValue("details");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
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
        $connection->execute("CALL sylius_payment_DB_PK($id)");
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
        $this->setCredit_card_id($arrayAccessor->getIntegerValue("credit_card_id"));
        $this->setOrder_id($arrayAccessor->getIntegerValue("order_id"));
        $this->setCurrency($arrayAccessor->getStringValue("currency"));
        $this->setAmount($arrayAccessor->getIntegerValue("amount"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setDetails($arrayAccessor->getStringValue("details"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->_existing = ($this->id !== null);
        $D9191BD419883967Array = $arrayAccessor->getArray("D9191BD419883967");
        if ($D9191BD419883967Array !== null) {
            $D9191BD419883967 = sylius_payment_methodService::getInstance()->newInstance();
            $D9191BD419883967->fromArray($D9191BD419883967Array);
            $this->setD9191BD419883967($D9191BD419883967);
        }
        $D9191BD47048FD0FArray = $arrayAccessor->getArray("D9191BD47048FD0F");
        if ($D9191BD47048FD0FArray !== null) {
            $D9191BD47048FD0F = sylius_credit_cardService::getInstance()->newInstance();
            $D9191BD47048FD0F->fromArray($D9191BD47048FD0FArray);
            $this->setD9191BD47048FD0F($D9191BD47048FD0F);
        }
        $D9191BD48D9F6D38Array = $arrayAccessor->getArray("D9191BD48D9F6D38");
        if ($D9191BD48D9F6D38Array !== null) {
            $D9191BD48D9F6D38 = sylius_orderService::getInstance()->newInstance();
            $D9191BD48D9F6D38->fromArray($D9191BD48D9F6D38Array);
            $this->setD9191BD48D9F6D38($D9191BD48D9F6D38);
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
            "credit_card_id" => $this->getCredit_card_id(),
            "order_id" => $this->getOrder_id(),
            "currency" => $this->getCurrency(),
            "amount" => $this->getAmount(),
            "state" => $this->getState(),
            "details" => $this->getDetails(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null
        ];
        if ($this->D9191BD419883967 !== null) {
            $result["D9191BD419883967"] = $this->D9191BD419883967->toArray($cycleDetector);
        }
        if ($this->D9191BD47048FD0F !== null) {
            $result["D9191BD47048FD0F"] = $this->D9191BD47048FD0F->toArray($cycleDetector);
        }
        if ($this->D9191BD48D9F6D38 !== null) {
            $result["D9191BD48D9F6D38"] = $this->D9191BD48D9F6D38->toArray($cycleDetector);
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
    public function getCredit_card_id()
    {
        return $this->credit_card_id;
    }

    /**
     * @param int $credit_card_id
     * 
     * @return void
     */
    public function setCredit_card_id(int $credit_card_id = null)
    {
        $this->credit_card_id = $credit_card_id;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * 
     * @return void
     */
    public function setCurrency(string $currency = null)
    {
        $this->currency = StringUtil::trimToNull($currency, 255);
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
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     * 
     * @return void
     */
    public function setDetails(string $details = null)
    {
        $this->details = StringUtil::trimToNull($details, null);
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
     * @return SiestaDateTime|null
     */
    public function getDeleted_at()
    {
        return $this->deleted_at;
    }

    /**
     * @param SiestaDateTime $deleted_at
     * 
     * @return void
     */
    public function setDeleted_at(SiestaDateTime $deleted_at = null)
    {
        $this->deleted_at = $deleted_at;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_payment_method|null
     */
    public function getD9191BD419883967(bool $forceReload = false)
    {
        if ($this->D9191BD419883967 === null || $forceReload) {
            $this->D9191BD419883967 = sylius_payment_methodService::getInstance()->getEntityByPrimaryKey($this->method_id);
        }
        return $this->D9191BD419883967;
    }

    /**
     * @param sylius_payment_method $entity
     * 
     * @return void
     */
    public function setD9191BD419883967(sylius_payment_method $entity = null)
    {
        $this->D9191BD419883967 = $entity;
        $this->method_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_credit_card|null
     */
    public function getD9191BD47048FD0F(bool $forceReload = false)
    {
        if ($this->D9191BD47048FD0F === null || $forceReload) {
            $this->D9191BD47048FD0F = sylius_credit_cardService::getInstance()->getEntityByPrimaryKey($this->credit_card_id);
        }
        return $this->D9191BD47048FD0F;
    }

    /**
     * @param sylius_credit_card $entity
     * 
     * @return void
     */
    public function setD9191BD47048FD0F(sylius_credit_card $entity = null)
    {
        $this->D9191BD47048FD0F = $entity;
        $this->credit_card_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_order|null
     */
    public function getD9191BD48D9F6D38(bool $forceReload = false)
    {
        if ($this->D9191BD48D9F6D38 === null || $forceReload) {
            $this->D9191BD48D9F6D38 = sylius_orderService::getInstance()->getEntityByPrimaryKey($this->order_id);
        }
        return $this->D9191BD48D9F6D38;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return void
     */
    public function setD9191BD48D9F6D38(sylius_order $entity = null)
    {
        $this->D9191BD48D9F6D38 = $entity;
        $this->order_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_payment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_payment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}