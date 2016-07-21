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

class sylius_order implements ArraySerializable
{

    const TABLE_NAME = "sylius_order";

    const COLUMN_ID = "id";

    const COLUMN_CHANNEL_ID = "channel_id";

    const COLUMN_SHIPPING_ADDRESS_ID = "shipping_address_id";

    const COLUMN_BILLING_ADDRESS_ID = "billing_address_id";

    const COLUMN_CUSTOMER_ID = "customer_id";

    const COLUMN_NUMBER = "number";

    const COLUMN_STATE = "state";

    const COLUMN_COMPLETED_AT = "completed_at";

    const COLUMN_ITEMS_TOTAL = "items_total";

    const COLUMN_ADJUSTMENTS_TOTAL = "adjustments_total";

    const COLUMN_TOTAL = "total";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

    const COLUMN_EXPIRES_AT = "expires_at";

    const COLUMN_CURRENCY = "currency";

    const COLUMN_CHECKOUT_STATE = "checkout_state";

    const COLUMN_PAYMENT_STATE = "payment_state";

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
    protected $channel_id;

    /**
     * @var int
     */
    protected $shipping_address_id;

    /**
     * @var int
     */
    protected $billing_address_id;

    /**
     * @var int
     */
    protected $customer_id;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var SiestaDateTime
     */
    protected $completed_at;

    /**
     * @var int
     */
    protected $items_total;

    /**
     * @var int
     */
    protected $adjustments_total;

    /**
     * @var int
     */
    protected $total;

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
     * @var SiestaDateTime
     */
    protected $expires_at;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $checkout_state;

    /**
     * @var string
     */
    protected $payment_state;

    /**
     * @var string
     */
    protected $shipping_state;

    /**
     * @var sylius_address
     */
    protected $6196A1F94D4CFF2B;

    /**
     * @var sylius_channel
     */
    protected $6196A1F972F5A1AA;

    /**
     * @var sylius_address
     */
    protected $6196A1F979D0C0E4;

    /**
     * @var sylius_customer
     */
    protected $6196A1F99395C3F3;

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
        $spCall = ($this->_existing) ? "CALL sylius_order_U(" : "CALL sylius_order_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->channel_id) . ',' . Escaper::quoteInt($this->shipping_address_id) . ',' . Escaper::quoteInt($this->billing_address_id) . ',' . Escaper::quoteInt($this->customer_id) . ',' . Escaper::quoteString($connection, $this->number) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteDateTime($this->completed_at) . ',' . Escaper::quoteInt($this->items_total) . ',' . Escaper::quoteInt($this->adjustments_total) . ',' . Escaper::quoteInt($this->total) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ',' . Escaper::quoteDateTime($this->expires_at) . ',' . Escaper::quoteString($connection, $this->currency) . ',' . Escaper::quoteString($connection, $this->checkout_state) . ',' . Escaper::quoteString($connection, $this->payment_state) . ',' . Escaper::quoteString($connection, $this->shipping_state) . ');';
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
        if ($cascade && $this->6196A1F94D4CFF2B !== null) {
            $this->6196A1F94D4CFF2B->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->6196A1F972F5A1AA !== null) {
            $this->6196A1F972F5A1AA->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->6196A1F979D0C0E4 !== null) {
            $this->6196A1F979D0C0E4->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->6196A1F99395C3F3 !== null) {
            $this->6196A1F99395C3F3->save($cascade, $cycleDetector, $connectionName);
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
        $this->channel_id = $resultSet->getIntegerValue("channel_id");
        $this->shipping_address_id = $resultSet->getIntegerValue("shipping_address_id");
        $this->billing_address_id = $resultSet->getIntegerValue("billing_address_id");
        $this->customer_id = $resultSet->getIntegerValue("customer_id");
        $this->number = $resultSet->getStringValue("number");
        $this->state = $resultSet->getStringValue("state");
        $this->completed_at = $resultSet->getDateTime("completed_at");
        $this->items_total = $resultSet->getIntegerValue("items_total");
        $this->adjustments_total = $resultSet->getIntegerValue("adjustments_total");
        $this->total = $resultSet->getIntegerValue("total");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
        $this->expires_at = $resultSet->getDateTime("expires_at");
        $this->currency = $resultSet->getStringValue("currency");
        $this->checkout_state = $resultSet->getStringValue("checkout_state");
        $this->payment_state = $resultSet->getStringValue("payment_state");
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
        $connection->execute("CALL sylius_order_DB_PK($id)");
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
        $this->setChannel_id($arrayAccessor->getIntegerValue("channel_id"));
        $this->setShipping_address_id($arrayAccessor->getIntegerValue("shipping_address_id"));
        $this->setBilling_address_id($arrayAccessor->getIntegerValue("billing_address_id"));
        $this->setCustomer_id($arrayAccessor->getIntegerValue("customer_id"));
        $this->setNumber($arrayAccessor->getStringValue("number"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setCompleted_at($arrayAccessor->getDateTime("completed_at"));
        $this->setItems_total($arrayAccessor->getIntegerValue("items_total"));
        $this->setAdjustments_total($arrayAccessor->getIntegerValue("adjustments_total"));
        $this->setTotal($arrayAccessor->getIntegerValue("total"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->setExpires_at($arrayAccessor->getDateTime("expires_at"));
        $this->setCurrency($arrayAccessor->getStringValue("currency"));
        $this->setCheckout_state($arrayAccessor->getStringValue("checkout_state"));
        $this->setPayment_state($arrayAccessor->getStringValue("payment_state"));
        $this->setShipping_state($arrayAccessor->getStringValue("shipping_state"));
        $this->_existing = ($this->id !== null);
        $6196A1F94D4CFF2BArray = $arrayAccessor->getArray("6196A1F94D4CFF2B");
        if ($6196A1F94D4CFF2BArray !== null) {
            $6196A1F94D4CFF2B = sylius_addressService::getInstance()->newInstance();
            $6196A1F94D4CFF2B->fromArray($6196A1F94D4CFF2BArray);
            $this->set6196A1F94D4CFF2B($6196A1F94D4CFF2B);
        }
        $6196A1F972F5A1AAArray = $arrayAccessor->getArray("6196A1F972F5A1AA");
        if ($6196A1F972F5A1AAArray !== null) {
            $6196A1F972F5A1AA = sylius_channelService::getInstance()->newInstance();
            $6196A1F972F5A1AA->fromArray($6196A1F972F5A1AAArray);
            $this->set6196A1F972F5A1AA($6196A1F972F5A1AA);
        }
        $6196A1F979D0C0E4Array = $arrayAccessor->getArray("6196A1F979D0C0E4");
        if ($6196A1F979D0C0E4Array !== null) {
            $6196A1F979D0C0E4 = sylius_addressService::getInstance()->newInstance();
            $6196A1F979D0C0E4->fromArray($6196A1F979D0C0E4Array);
            $this->set6196A1F979D0C0E4($6196A1F979D0C0E4);
        }
        $6196A1F99395C3F3Array = $arrayAccessor->getArray("6196A1F99395C3F3");
        if ($6196A1F99395C3F3Array !== null) {
            $6196A1F99395C3F3 = sylius_customerService::getInstance()->newInstance();
            $6196A1F99395C3F3->fromArray($6196A1F99395C3F3Array);
            $this->set6196A1F99395C3F3($6196A1F99395C3F3);
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
            "channel_id" => $this->getChannel_id(),
            "shipping_address_id" => $this->getShipping_address_id(),
            "billing_address_id" => $this->getBilling_address_id(),
            "customer_id" => $this->getCustomer_id(),
            "number" => $this->getNumber(),
            "state" => $this->getState(),
            "completed_at" => ($this->getCompleted_at() !== null) ? $this->getCompleted_at()->getJSONDateTime() : null,
            "items_total" => $this->getItems_total(),
            "adjustments_total" => $this->getAdjustments_total(),
            "total" => $this->getTotal(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null,
            "expires_at" => ($this->getExpires_at() !== null) ? $this->getExpires_at()->getJSONDateTime() : null,
            "currency" => $this->getCurrency(),
            "checkout_state" => $this->getCheckout_state(),
            "payment_state" => $this->getPayment_state(),
            "shipping_state" => $this->getShipping_state()
        ];
        if ($this->6196A1F94D4CFF2B !== null) {
            $result["6196A1F94D4CFF2B"] = $this->6196A1F94D4CFF2B->toArray($cycleDetector);
        }
        if ($this->6196A1F972F5A1AA !== null) {
            $result["6196A1F972F5A1AA"] = $this->6196A1F972F5A1AA->toArray($cycleDetector);
        }
        if ($this->6196A1F979D0C0E4 !== null) {
            $result["6196A1F979D0C0E4"] = $this->6196A1F979D0C0E4->toArray($cycleDetector);
        }
        if ($this->6196A1F99395C3F3 !== null) {
            $result["6196A1F99395C3F3"] = $this->6196A1F99395C3F3->toArray($cycleDetector);
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
    public function getChannel_id()
    {
        return $this->channel_id;
    }

    /**
     * @param int $channel_id
     * 
     * @return void
     */
    public function setChannel_id(int $channel_id = null)
    {
        $this->channel_id = $channel_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getShipping_address_id()
    {
        return $this->shipping_address_id;
    }

    /**
     * @param int $shipping_address_id
     * 
     * @return void
     */
    public function setShipping_address_id(int $shipping_address_id = null)
    {
        $this->shipping_address_id = $shipping_address_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getBilling_address_id()
    {
        return $this->billing_address_id;
    }

    /**
     * @param int $billing_address_id
     * 
     * @return void
     */
    public function setBilling_address_id(int $billing_address_id = null)
    {
        $this->billing_address_id = $billing_address_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getCustomer_id()
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * 
     * @return void
     */
    public function setCustomer_id(int $customer_id = null)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * 
     * @return void
     */
    public function setNumber(string $number = null)
    {
        $this->number = StringUtil::trimToNull($number, 255);
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
     * @return SiestaDateTime|null
     */
    public function getCompleted_at()
    {
        return $this->completed_at;
    }

    /**
     * @param SiestaDateTime $completed_at
     * 
     * @return void
     */
    public function setCompleted_at(SiestaDateTime $completed_at = null)
    {
        $this->completed_at = $completed_at;
    }

    /**
     * 
     * @return int|null
     */
    public function getItems_total()
    {
        return $this->items_total;
    }

    /**
     * @param int $items_total
     * 
     * @return void
     */
    public function setItems_total(int $items_total = null)
    {
        $this->items_total = $items_total;
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
     * 
     * @return SiestaDateTime|null
     */
    public function getExpires_at()
    {
        return $this->expires_at;
    }

    /**
     * @param SiestaDateTime $expires_at
     * 
     * @return void
     */
    public function setExpires_at(SiestaDateTime $expires_at = null)
    {
        $this->expires_at = $expires_at;
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
        $this->currency = StringUtil::trimToNull($currency, 3);
    }

    /**
     * 
     * @return string|null
     */
    public function getCheckout_state()
    {
        return $this->checkout_state;
    }

    /**
     * @param string $checkout_state
     * 
     * @return void
     */
    public function setCheckout_state(string $checkout_state = null)
    {
        $this->checkout_state = StringUtil::trimToNull($checkout_state, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPayment_state()
    {
        return $this->payment_state;
    }

    /**
     * @param string $payment_state
     * 
     * @return void
     */
    public function setPayment_state(string $payment_state = null)
    {
        $this->payment_state = StringUtil::trimToNull($payment_state, 255);
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
     * @return sylius_address|null
     */
    public function get6196A1F94D4CFF2B(bool $forceReload = false)
    {
        if ($this->6196A1F94D4CFF2B === null || $forceReload) {
            $this->6196A1F94D4CFF2B = sylius_addressService::getInstance()->getEntityByPrimaryKey($this->shipping_address_id);
        }
        return $this->6196A1F94D4CFF2B;
    }

    /**
     * @param sylius_address $entity
     * 
     * @return void
     */
    public function set6196A1F94D4CFF2B(sylius_address $entity = null)
    {
        $this->6196A1F94D4CFF2B = $entity;
        $this->shipping_address_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function get6196A1F972F5A1AA(bool $forceReload = false)
    {
        if ($this->6196A1F972F5A1AA === null || $forceReload) {
            $this->6196A1F972F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->6196A1F972F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function set6196A1F972F5A1AA(sylius_channel $entity = null)
    {
        $this->6196A1F972F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_address|null
     */
    public function get6196A1F979D0C0E4(bool $forceReload = false)
    {
        if ($this->6196A1F979D0C0E4 === null || $forceReload) {
            $this->6196A1F979D0C0E4 = sylius_addressService::getInstance()->getEntityByPrimaryKey($this->billing_address_id);
        }
        return $this->6196A1F979D0C0E4;
    }

    /**
     * @param sylius_address $entity
     * 
     * @return void
     */
    public function set6196A1F979D0C0E4(sylius_address $entity = null)
    {
        $this->6196A1F979D0C0E4 = $entity;
        $this->billing_address_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_customer|null
     */
    public function get6196A1F99395C3F3(bool $forceReload = false)
    {
        if ($this->6196A1F99395C3F3 === null || $forceReload) {
            $this->6196A1F99395C3F3 = sylius_customerService::getInstance()->getEntityByPrimaryKey($this->customer_id);
        }
        return $this->6196A1F99395C3F3;
    }

    /**
     * @param sylius_customer $entity
     * 
     * @return void
     */
    public function set6196A1F99395C3F3(sylius_customer $entity = null)
    {
        $this->6196A1F99395C3F3 = $entity;
        $this->customer_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_order $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}