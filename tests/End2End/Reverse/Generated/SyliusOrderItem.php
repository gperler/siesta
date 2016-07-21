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
use Siesta\Util\StringUtil;

class SyliusOrderItem implements ArraySerializable
{

    const TABLE_NAME = "sylius_order_item";

    const COLUMN_ID = "id";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_VARIANTID = "variant_id";

    const COLUMN_QUANTITY = "quantity";

    const COLUMN_UNITPRICE = "unit_price";

    const COLUMN_ADJUSTMENTSTOTAL = "adjustments_total";

    const COLUMN_TOTAL = "total";

    const COLUMN_ISIMMUTABLE = "is_immutable";

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
    protected $variantId;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $unitPrice;

    /**
     * @var int
     */
    protected $adjustmentsTotal;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var string
     */
    protected $isImmutable;

    /**
     * @var SyliusProductVariant
     */
    protected $77B587ED3B69A9AF;

    /**
     * @var SyliusOrder
     */
    protected $77B587ED8D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_order_item_U(" : "CALL sylius_order_item_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteInt($this->variantId) . ',' . Escaper::quoteInt($this->quantity) . ',' . Escaper::quoteInt($this->unitPrice) . ',' . Escaper::quoteInt($this->adjustmentsTotal) . ',' . Escaper::quoteInt($this->total) . ',' . Escaper::quoteString($connection, $this->isImmutable) . ');';
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
        if ($cascade && $this->77B587ED3B69A9AF !== null) {
            $this->77B587ED3B69A9AF->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->77B587ED8D9F6D38 !== null) {
            $this->77B587ED8D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->variantId = $resultSet->getIntegerValue("variant_id");
        $this->quantity = $resultSet->getIntegerValue("quantity");
        $this->unitPrice = $resultSet->getIntegerValue("unit_price");
        $this->adjustmentsTotal = $resultSet->getIntegerValue("adjustments_total");
        $this->total = $resultSet->getIntegerValue("total");
        $this->isImmutable = $resultSet->getStringValue("is_immutable");
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
        $connection->execute("CALL sylius_order_item_DB_PK($id)");
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
        $this->setVariantId($arrayAccessor->getIntegerValue("variantId"));
        $this->setQuantity($arrayAccessor->getIntegerValue("quantity"));
        $this->setUnitPrice($arrayAccessor->getIntegerValue("unitPrice"));
        $this->setAdjustmentsTotal($arrayAccessor->getIntegerValue("adjustmentsTotal"));
        $this->setTotal($arrayAccessor->getIntegerValue("total"));
        $this->setIsImmutable($arrayAccessor->getStringValue("isImmutable"));
        $this->_existing = ($this->id !== null);
        $77B587ED3B69A9AFArray = $arrayAccessor->getArray("77B587ED3B69A9AF");
        if ($77B587ED3B69A9AFArray !== null) {
            $77B587ED3B69A9AF = SyliusProductVariantService::getInstance()->newInstance();
            $77B587ED3B69A9AF->fromArray($77B587ED3B69A9AFArray);
            $this->set77B587ED3B69A9AF($77B587ED3B69A9AF);
        }
        $77B587ED8D9F6D38Array = $arrayAccessor->getArray("77B587ED8D9F6D38");
        if ($77B587ED8D9F6D38Array !== null) {
            $77B587ED8D9F6D38 = SyliusOrderService::getInstance()->newInstance();
            $77B587ED8D9F6D38->fromArray($77B587ED8D9F6D38Array);
            $this->set77B587ED8D9F6D38($77B587ED8D9F6D38);
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
            "variantId" => $this->getVariantId(),
            "quantity" => $this->getQuantity(),
            "unitPrice" => $this->getUnitPrice(),
            "adjustmentsTotal" => $this->getAdjustmentsTotal(),
            "total" => $this->getTotal(),
            "isImmutable" => $this->getIsImmutable()
        ];
        if ($this->77B587ED3B69A9AF !== null) {
            $result["77B587ED3B69A9AF"] = $this->77B587ED3B69A9AF->toArray($cycleDetector);
        }
        if ($this->77B587ED8D9F6D38 !== null) {
            $result["77B587ED8D9F6D38"] = $this->77B587ED8D9F6D38->toArray($cycleDetector);
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
    public function getVariantId()
    {
        return $this->variantId;
    }

    /**
     * @param int $variantId
     * 
     * @return void
     */
    public function setVariantId(int $variantId = null)
    {
        $this->variantId = $variantId;
    }

    /**
     * 
     * @return int|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * 
     * @return void
     */
    public function setQuantity(int $quantity = null)
    {
        $this->quantity = $quantity;
    }

    /**
     * 
     * @return int|null
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     * 
     * @return void
     */
    public function setUnitPrice(int $unitPrice = null)
    {
        $this->unitPrice = $unitPrice;
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
     * @return string|null
     */
    public function getIsImmutable()
    {
        return $this->isImmutable;
    }

    /**
     * @param string $isImmutable
     * 
     * @return void
     */
    public function setIsImmutable(string $isImmutable = null)
    {
        $this->isImmutable = StringUtil::trimToNull($isImmutable, null);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductVariant|null
     */
    public function get77B587ED3B69A9AF(bool $forceReload = false)
    {
        if ($this->77B587ED3B69A9AF === null || $forceReload) {
            $this->77B587ED3B69A9AF = SyliusProductVariantService::getInstance()->getEntityByPrimaryKey($this->variantId);
        }
        return $this->77B587ED3B69A9AF;
    }

    /**
     * @param SyliusProductVariant $entity
     * 
     * @return void
     */
    public function set77B587ED3B69A9AF(SyliusProductVariant $entity = null)
    {
        $this->77B587ED3B69A9AF = $entity;
        $this->variantId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusOrder|null
     */
    public function get77B587ED8D9F6D38(bool $forceReload = false)
    {
        if ($this->77B587ED8D9F6D38 === null || $forceReload) {
            $this->77B587ED8D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->77B587ED8D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function set77B587ED8D9F6D38(SyliusOrder $entity = null)
    {
        $this->77B587ED8D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusOrderItem $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusOrderItem $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}