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

class SyliusPayment implements ArraySerializable
{

    const TABLE_NAME = "sylius_payment";

    const COLUMN_ID = "id";

    const COLUMN_METHODID = "method_id";

    const COLUMN_CREDITCARDID = "credit_card_id";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_CURRENCY = "currency";

    const COLUMN_AMOUNT = "amount";

    const COLUMN_STATE = "state";

    const COLUMN_DETAILS = "details";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

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
    protected $creditCardId;

    /**
     * @var int
     */
    protected $orderId;

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
     * @var SyliusPaymentMethod
     */
    protected $D9191BD419883967;

    /**
     * @var SyliusCreditCard
     */
    protected $D9191BD47048FD0F;

    /**
     * @var SyliusOrder
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->methodId) . ',' . Escaper::quoteInt($this->creditCardId) . ',' . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteString($connection, $this->currency) . ',' . Escaper::quoteInt($this->amount) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteString($connection, $this->details) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ');';
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
        $this->methodId = $resultSet->getIntegerValue("method_id");
        $this->creditCardId = $resultSet->getIntegerValue("credit_card_id");
        $this->orderId = $resultSet->getIntegerValue("order_id");
        $this->currency = $resultSet->getStringValue("currency");
        $this->amount = $resultSet->getIntegerValue("amount");
        $this->state = $resultSet->getStringValue("state");
        $this->details = $resultSet->getStringValue("details");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
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
        $this->setMethodId($arrayAccessor->getIntegerValue("methodId"));
        $this->setCreditCardId($arrayAccessor->getIntegerValue("creditCardId"));
        $this->setOrderId($arrayAccessor->getIntegerValue("orderId"));
        $this->setCurrency($arrayAccessor->getStringValue("currency"));
        $this->setAmount($arrayAccessor->getIntegerValue("amount"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setDetails($arrayAccessor->getStringValue("details"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->_existing = ($this->id !== null);
        $D9191BD419883967Array = $arrayAccessor->getArray("D9191BD419883967");
        if ($D9191BD419883967Array !== null) {
            $D9191BD419883967 = SyliusPaymentMethodService::getInstance()->newInstance();
            $D9191BD419883967->fromArray($D9191BD419883967Array);
            $this->setD9191BD419883967($D9191BD419883967);
        }
        $D9191BD47048FD0FArray = $arrayAccessor->getArray("D9191BD47048FD0F");
        if ($D9191BD47048FD0FArray !== null) {
            $D9191BD47048FD0F = SyliusCreditCardService::getInstance()->newInstance();
            $D9191BD47048FD0F->fromArray($D9191BD47048FD0FArray);
            $this->setD9191BD47048FD0F($D9191BD47048FD0F);
        }
        $D9191BD48D9F6D38Array = $arrayAccessor->getArray("D9191BD48D9F6D38");
        if ($D9191BD48D9F6D38Array !== null) {
            $D9191BD48D9F6D38 = SyliusOrderService::getInstance()->newInstance();
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
            "methodId" => $this->getMethodId(),
            "creditCardId" => $this->getCreditCardId(),
            "orderId" => $this->getOrderId(),
            "currency" => $this->getCurrency(),
            "amount" => $this->getAmount(),
            "state" => $this->getState(),
            "details" => $this->getDetails(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null
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
    public function getCreditCardId()
    {
        return $this->creditCardId;
    }

    /**
     * @param int $creditCardId
     * 
     * @return void
     */
    public function setCreditCardId(int $creditCardId = null)
    {
        $this->creditCardId = $creditCardId;
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
     * @param bool $forceReload
     * 
     * @return SyliusPaymentMethod|null
     */
    public function getD9191BD419883967(bool $forceReload = false)
    {
        if ($this->D9191BD419883967 === null || $forceReload) {
            $this->D9191BD419883967 = SyliusPaymentMethodService::getInstance()->getEntityByPrimaryKey($this->methodId);
        }
        return $this->D9191BD419883967;
    }

    /**
     * @param SyliusPaymentMethod $entity
     * 
     * @return void
     */
    public function setD9191BD419883967(SyliusPaymentMethod $entity = null)
    {
        $this->D9191BD419883967 = $entity;
        $this->methodId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCreditCard|null
     */
    public function getD9191BD47048FD0F(bool $forceReload = false)
    {
        if ($this->D9191BD47048FD0F === null || $forceReload) {
            $this->D9191BD47048FD0F = SyliusCreditCardService::getInstance()->getEntityByPrimaryKey($this->creditCardId);
        }
        return $this->D9191BD47048FD0F;
    }

    /**
     * @param SyliusCreditCard $entity
     * 
     * @return void
     */
    public function setD9191BD47048FD0F(SyliusCreditCard $entity = null)
    {
        $this->D9191BD47048FD0F = $entity;
        $this->creditCardId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrder|null
     */
    public function getD9191BD48D9F6D38(bool $forceReload = false)
    {
        if ($this->D9191BD48D9F6D38 === null || $forceReload) {
            $this->D9191BD48D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->D9191BD48D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function setD9191BD48D9F6D38(SyliusOrder $entity = null)
    {
        $this->D9191BD48D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusPayment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPayment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}