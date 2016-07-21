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

class SyliusOrder implements ArraySerializable
{

    const TABLE_NAME = "sylius_order";

    const COLUMN_ID = "id";

    const COLUMN_CHANNELID = "channel_id";

    const COLUMN_SHIPPINGADDRESSID = "shipping_address_id";

    const COLUMN_BILLINGADDRESSID = "billing_address_id";

    const COLUMN_CUSTOMERID = "customer_id";

    const COLUMN_NUMBER = "number";

    const COLUMN_STATE = "state";

    const COLUMN_COMPLETEDAT = "completed_at";

    const COLUMN_ITEMSTOTAL = "items_total";

    const COLUMN_ADJUSTMENTSTOTAL = "adjustments_total";

    const COLUMN_TOTAL = "total";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

    const COLUMN_EXPIRESAT = "expires_at";

    const COLUMN_CURRENCY = "currency";

    const COLUMN_CHECKOUTSTATE = "checkout_state";

    const COLUMN_PAYMENTSTATE = "payment_state";

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
    protected $channelId;

    /**
     * @var int
     */
    protected $shippingAddressId;

    /**
     * @var int
     */
    protected $billingAddressId;

    /**
     * @var int
     */
    protected $customerId;

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
    protected $completedAt;

    /**
     * @var int
     */
    protected $itemsTotal;

    /**
     * @var int
     */
    protected $adjustmentsTotal;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SiestaDateTime
     */
    protected $deletedAt;

    /**
     * @var SiestaDateTime
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $checkoutState;

    /**
     * @var string
     */
    protected $paymentState;

    /**
     * @var string
     */
    protected $shippingState;

    /**
     * @var SyliusAddress
     */
    protected $6196A1F94D4CFF2B;

    /**
     * @var SyliusChannel
     */
    protected $6196A1F972F5A1AA;

    /**
     * @var SyliusAddress
     */
    protected $6196A1F979D0C0E4;

    /**
     * @var SyliusCustomer
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->channelId) . ',' . Escaper::quoteInt($this->shippingAddressId) . ',' . Escaper::quoteInt($this->billingAddressId) . ',' . Escaper::quoteInt($this->customerId) . ',' . Escaper::quoteString($connection, $this->number) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteDateTime($this->completedAt) . ',' . Escaper::quoteInt($this->itemsTotal) . ',' . Escaper::quoteInt($this->adjustmentsTotal) . ',' . Escaper::quoteInt($this->total) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ',' . Escaper::quoteDateTime($this->expiresAt) . ',' . Escaper::quoteString($connection, $this->currency) . ',' . Escaper::quoteString($connection, $this->checkoutState) . ',' . Escaper::quoteString($connection, $this->paymentState) . ',' . Escaper::quoteString($connection, $this->shippingState) . ');';
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
        $this->channelId = $resultSet->getIntegerValue("channel_id");
        $this->shippingAddressId = $resultSet->getIntegerValue("shipping_address_id");
        $this->billingAddressId = $resultSet->getIntegerValue("billing_address_id");
        $this->customerId = $resultSet->getIntegerValue("customer_id");
        $this->number = $resultSet->getStringValue("number");
        $this->state = $resultSet->getStringValue("state");
        $this->completedAt = $resultSet->getDateTime("completed_at");
        $this->itemsTotal = $resultSet->getIntegerValue("items_total");
        $this->adjustmentsTotal = $resultSet->getIntegerValue("adjustments_total");
        $this->total = $resultSet->getIntegerValue("total");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
        $this->expiresAt = $resultSet->getDateTime("expires_at");
        $this->currency = $resultSet->getStringValue("currency");
        $this->checkoutState = $resultSet->getStringValue("checkout_state");
        $this->paymentState = $resultSet->getStringValue("payment_state");
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
        $this->setChannelId($arrayAccessor->getIntegerValue("channelId"));
        $this->setShippingAddressId($arrayAccessor->getIntegerValue("shippingAddressId"));
        $this->setBillingAddressId($arrayAccessor->getIntegerValue("billingAddressId"));
        $this->setCustomerId($arrayAccessor->getIntegerValue("customerId"));
        $this->setNumber($arrayAccessor->getStringValue("number"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setCompletedAt($arrayAccessor->getDateTime("completedAt"));
        $this->setItemsTotal($arrayAccessor->getIntegerValue("itemsTotal"));
        $this->setAdjustmentsTotal($arrayAccessor->getIntegerValue("adjustmentsTotal"));
        $this->setTotal($arrayAccessor->getIntegerValue("total"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->setExpiresAt($arrayAccessor->getDateTime("expiresAt"));
        $this->setCurrency($arrayAccessor->getStringValue("currency"));
        $this->setCheckoutState($arrayAccessor->getStringValue("checkoutState"));
        $this->setPaymentState($arrayAccessor->getStringValue("paymentState"));
        $this->setShippingState($arrayAccessor->getStringValue("shippingState"));
        $this->_existing = ($this->id !== null);
        $6196A1F94D4CFF2BArray = $arrayAccessor->getArray("6196A1F94D4CFF2B");
        if ($6196A1F94D4CFF2BArray !== null) {
            $6196A1F94D4CFF2B = SyliusAddressService::getInstance()->newInstance();
            $6196A1F94D4CFF2B->fromArray($6196A1F94D4CFF2BArray);
            $this->set6196A1F94D4CFF2B($6196A1F94D4CFF2B);
        }
        $6196A1F972F5A1AAArray = $arrayAccessor->getArray("6196A1F972F5A1AA");
        if ($6196A1F972F5A1AAArray !== null) {
            $6196A1F972F5A1AA = SyliusChannelService::getInstance()->newInstance();
            $6196A1F972F5A1AA->fromArray($6196A1F972F5A1AAArray);
            $this->set6196A1F972F5A1AA($6196A1F972F5A1AA);
        }
        $6196A1F979D0C0E4Array = $arrayAccessor->getArray("6196A1F979D0C0E4");
        if ($6196A1F979D0C0E4Array !== null) {
            $6196A1F979D0C0E4 = SyliusAddressService::getInstance()->newInstance();
            $6196A1F979D0C0E4->fromArray($6196A1F979D0C0E4Array);
            $this->set6196A1F979D0C0E4($6196A1F979D0C0E4);
        }
        $6196A1F99395C3F3Array = $arrayAccessor->getArray("6196A1F99395C3F3");
        if ($6196A1F99395C3F3Array !== null) {
            $6196A1F99395C3F3 = SyliusCustomerService::getInstance()->newInstance();
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
            "channelId" => $this->getChannelId(),
            "shippingAddressId" => $this->getShippingAddressId(),
            "billingAddressId" => $this->getBillingAddressId(),
            "customerId" => $this->getCustomerId(),
            "number" => $this->getNumber(),
            "state" => $this->getState(),
            "completedAt" => ($this->getCompletedAt() !== null) ? $this->getCompletedAt()->getJSONDateTime() : null,
            "itemsTotal" => $this->getItemsTotal(),
            "adjustmentsTotal" => $this->getAdjustmentsTotal(),
            "total" => $this->getTotal(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null,
            "expiresAt" => ($this->getExpiresAt() !== null) ? $this->getExpiresAt()->getJSONDateTime() : null,
            "currency" => $this->getCurrency(),
            "checkoutState" => $this->getCheckoutState(),
            "paymentState" => $this->getPaymentState(),
            "shippingState" => $this->getShippingState()
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
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * @param int $channelId
     * 
     * @return void
     */
    public function setChannelId(int $channelId = null)
    {
        $this->channelId = $channelId;
    }

    /**
     * 
     * @return int|null
     */
    public function getShippingAddressId()
    {
        return $this->shippingAddressId;
    }

    /**
     * @param int $shippingAddressId
     * 
     * @return void
     */
    public function setShippingAddressId(int $shippingAddressId = null)
    {
        $this->shippingAddressId = $shippingAddressId;
    }

    /**
     * 
     * @return int|null
     */
    public function getBillingAddressId()
    {
        return $this->billingAddressId;
    }

    /**
     * @param int $billingAddressId
     * 
     * @return void
     */
    public function setBillingAddressId(int $billingAddressId = null)
    {
        $this->billingAddressId = $billingAddressId;
    }

    /**
     * 
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     * 
     * @return void
     */
    public function setCustomerId(int $customerId = null)
    {
        $this->customerId = $customerId;
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
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * @param SiestaDateTime $completedAt
     * 
     * @return void
     */
    public function setCompletedAt(SiestaDateTime $completedAt = null)
    {
        $this->completedAt = $completedAt;
    }

    /**
     * 
     * @return int|null
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * @param int $itemsTotal
     * 
     * @return void
     */
    public function setItemsTotal(int $itemsTotal = null)
    {
        $this->itemsTotal = $itemsTotal;
    }

    /**
     * 
     * @return int|null
     */
    public function getAdjustmentsTotal()
    {
        return $this->adjustmentsTotal;
    }

    /**
     * @param int $adjustmentsTotal
     * 
     * @return void
     */
    public function setAdjustmentsTotal(int $adjustmentsTotal = null)
    {
        $this->adjustmentsTotal = $adjustmentsTotal;
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
     * @return SiestaDateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param SiestaDateTime $deletedAt
     * 
     * @return void
     */
    public function setDeletedAt(SiestaDateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param SiestaDateTime $expiresAt
     * 
     * @return void
     */
    public function setExpiresAt(SiestaDateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
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
    public function getCheckoutState()
    {
        return $this->checkoutState;
    }

    /**
     * @param string $checkoutState
     * 
     * @return void
     */
    public function setCheckoutState(string $checkoutState = null)
    {
        $this->checkoutState = StringUtil::trimToNull($checkoutState, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * @param string $paymentState
     * 
     * @return void
     */
    public function setPaymentState(string $paymentState = null)
    {
        $this->paymentState = StringUtil::trimToNull($paymentState, 255);
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
     * @return SyliusAddress|null
     */
    public function get6196A1F94D4CFF2B(bool $forceReload = false)
    {
        if ($this->6196A1F94D4CFF2B === null || $forceReload) {
            $this->6196A1F94D4CFF2B = SyliusAddressService::getInstance()->getEntityByPrimaryKey($this->shippingAddressId);
        }
        return $this->6196A1F94D4CFF2B;
    }

    /**
     * @param SyliusAddress $entity
     * 
     * @return void
     */
    public function set6196A1F94D4CFF2B(SyliusAddress $entity = null)
    {
        $this->6196A1F94D4CFF2B = $entity;
        $this->shippingAddressId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function get6196A1F972F5A1AA(bool $forceReload = false)
    {
        if ($this->6196A1F972F5A1AA === null || $forceReload) {
            $this->6196A1F972F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->6196A1F972F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function set6196A1F972F5A1AA(SyliusChannel $entity = null)
    {
        $this->6196A1F972F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusAddress|null
     */
    public function get6196A1F979D0C0E4(bool $forceReload = false)
    {
        if ($this->6196A1F979D0C0E4 === null || $forceReload) {
            $this->6196A1F979D0C0E4 = SyliusAddressService::getInstance()->getEntityByPrimaryKey($this->billingAddressId);
        }
        return $this->6196A1F979D0C0E4;
    }

    /**
     * @param SyliusAddress $entity
     * 
     * @return void
     */
    public function set6196A1F979D0C0E4(SyliusAddress $entity = null)
    {
        $this->6196A1F979D0C0E4 = $entity;
        $this->billingAddressId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCustomer|null
     */
    public function get6196A1F99395C3F3(bool $forceReload = false)
    {
        if ($this->6196A1F99395C3F3 === null || $forceReload) {
            $this->6196A1F99395C3F3 = SyliusCustomerService::getInstance()->getEntityByPrimaryKey($this->customerId);
        }
        return $this->6196A1F99395C3F3;
    }

    /**
     * @param SyliusCustomer $entity
     * 
     * @return void
     */
    public function set6196A1F99395C3F3(SyliusCustomer $entity = null)
    {
        $this->6196A1F99395C3F3 = $entity;
        $this->customerId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusOrder $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}