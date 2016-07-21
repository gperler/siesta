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

class SyliusProductVariant implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_variant";

    const COLUMN_ID = "id";

    const COLUMN_PRODUCTID = "product_id";

    const COLUMN_ISMASTER = "is_master";

    const COLUMN_PRESENTATION = "presentation";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

    const COLUMN_AVAILABLEON = "available_on";

    const COLUMN_SKU = "sku";

    const COLUMN_ONHOLD = "on_hold";

    const COLUMN_ONHAND = "on_hand";

    const COLUMN_SOLD = "sold";

    const COLUMN_AVAILABLEONDEMAND = "available_on_demand";

    const COLUMN_PRICE = "price";

    const COLUMN_PRICINGCALCULATOR = "pricing_calculator";

    const COLUMN_PRICINGCONFIGURATION = "pricing_configuration";

    const COLUMN_WIDTH = "width";

    const COLUMN_HEIGHT = "height";

    const COLUMN_DEPTH = "depth";

    const COLUMN_WEIGHT = "weight";

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
    protected $productId;

    /**
     * @var string
     */
    protected $isMaster;

    /**
     * @var string
     */
    protected $presentation;

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
    protected $availableOn;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var int
     */
    protected $onHold;

    /**
     * @var int
     */
    protected $onHand;

    /**
     * @var int
     */
    protected $sold;

    /**
     * @var string
     */
    protected $availableOnDemand;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var string
     */
    protected $pricingCalculator;

    /**
     * @var string
     */
    protected $pricingConfiguration;

    /**
     * @var float
     */
    protected $width;

    /**
     * @var float
     */
    protected $height;

    /**
     * @var float
     */
    protected $depth;

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var SyliusProduct
     */
    protected $A29B5234584665A;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_variant_U(" : "CALL sylius_product_variant_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->productId) . ',' . Escaper::quoteString($connection, $this->isMaster) . ',' . Escaper::quoteString($connection, $this->presentation) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ',' . Escaper::quoteDateTime($this->availableOn) . ',' . Escaper::quoteString($connection, $this->sku) . ',' . Escaper::quoteInt($this->onHold) . ',' . Escaper::quoteInt($this->onHand) . ',' . Escaper::quoteInt($this->sold) . ',' . Escaper::quoteString($connection, $this->availableOnDemand) . ',' . Escaper::quoteInt($this->price) . ',' . Escaper::quoteString($connection, $this->pricingCalculator) . ',' . Escaper::quoteString($connection, $this->pricingConfiguration) . ',' . Escaper::quoteFloat($this->width) . ',' . Escaper::quoteFloat($this->height) . ',' . Escaper::quoteFloat($this->depth) . ',' . Escaper::quoteFloat($this->weight) . ');';
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
        if ($cascade && $this->A29B5234584665A !== null) {
            $this->A29B5234584665A->save($cascade, $cycleDetector, $connectionName);
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
        $this->productId = $resultSet->getIntegerValue("product_id");
        $this->isMaster = $resultSet->getStringValue("is_master");
        $this->presentation = $resultSet->getStringValue("presentation");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
        $this->availableOn = $resultSet->getDateTime("available_on");
        $this->sku = $resultSet->getStringValue("sku");
        $this->onHold = $resultSet->getIntegerValue("on_hold");
        $this->onHand = $resultSet->getIntegerValue("on_hand");
        $this->sold = $resultSet->getIntegerValue("sold");
        $this->availableOnDemand = $resultSet->getStringValue("available_on_demand");
        $this->price = $resultSet->getIntegerValue("price");
        $this->pricingCalculator = $resultSet->getStringValue("pricing_calculator");
        $this->pricingConfiguration = $resultSet->getStringValue("pricing_configuration");
        $this->width = $resultSet->getFloatValue("width");
        $this->height = $resultSet->getFloatValue("height");
        $this->depth = $resultSet->getFloatValue("depth");
        $this->weight = $resultSet->getFloatValue("weight");
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
        $connection->execute("CALL sylius_product_variant_DB_PK($id)");
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
        $this->setProductId($arrayAccessor->getIntegerValue("productId"));
        $this->setIsMaster($arrayAccessor->getStringValue("isMaster"));
        $this->setPresentation($arrayAccessor->getStringValue("presentation"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->setAvailableOn($arrayAccessor->getDateTime("availableOn"));
        $this->setSku($arrayAccessor->getStringValue("sku"));
        $this->setOnHold($arrayAccessor->getIntegerValue("onHold"));
        $this->setOnHand($arrayAccessor->getIntegerValue("onHand"));
        $this->setSold($arrayAccessor->getIntegerValue("sold"));
        $this->setAvailableOnDemand($arrayAccessor->getStringValue("availableOnDemand"));
        $this->setPrice($arrayAccessor->getIntegerValue("price"));
        $this->setPricingCalculator($arrayAccessor->getStringValue("pricingCalculator"));
        $this->setPricingConfiguration($arrayAccessor->getStringValue("pricingConfiguration"));
        $this->setWidth($arrayAccessor->getFloatValue("width"));
        $this->setHeight($arrayAccessor->getFloatValue("height"));
        $this->setDepth($arrayAccessor->getFloatValue("depth"));
        $this->setWeight($arrayAccessor->getFloatValue("weight"));
        $this->_existing = ($this->id !== null);
        $A29B5234584665AArray = $arrayAccessor->getArray("A29B5234584665A");
        if ($A29B5234584665AArray !== null) {
            $A29B5234584665A = SyliusProductService::getInstance()->newInstance();
            $A29B5234584665A->fromArray($A29B5234584665AArray);
            $this->setA29B5234584665A($A29B5234584665A);
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
            "productId" => $this->getProductId(),
            "isMaster" => $this->getIsMaster(),
            "presentation" => $this->getPresentation(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null,
            "availableOn" => ($this->getAvailableOn() !== null) ? $this->getAvailableOn()->getJSONDateTime() : null,
            "sku" => $this->getSku(),
            "onHold" => $this->getOnHold(),
            "onHand" => $this->getOnHand(),
            "sold" => $this->getSold(),
            "availableOnDemand" => $this->getAvailableOnDemand(),
            "price" => $this->getPrice(),
            "pricingCalculator" => $this->getPricingCalculator(),
            "pricingConfiguration" => $this->getPricingConfiguration(),
            "width" => $this->getWidth(),
            "height" => $this->getHeight(),
            "depth" => $this->getDepth(),
            "weight" => $this->getWeight()
        ];
        if ($this->A29B5234584665A !== null) {
            $result["A29B5234584665A"] = $this->A29B5234584665A->toArray($cycleDetector);
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
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * 
     * @return void
     */
    public function setProductId(int $productId = null)
    {
        $this->productId = $productId;
    }

    /**
     * 
     * @return string|null
     */
    public function getIsMaster()
    {
        return $this->isMaster;
    }

    /**
     * @param string $isMaster
     * 
     * @return void
     */
    public function setIsMaster(string $isMaster = null)
    {
        $this->isMaster = StringUtil::trimToNull($isMaster, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param string $presentation
     * 
     * @return void
     */
    public function setPresentation(string $presentation = null)
    {
        $this->presentation = StringUtil::trimToNull($presentation, 255);
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
    public function getAvailableOn()
    {
        return $this->availableOn;
    }

    /**
     * @param SiestaDateTime $availableOn
     * 
     * @return void
     */
    public function setAvailableOn(SiestaDateTime $availableOn = null)
    {
        $this->availableOn = $availableOn;
    }

    /**
     * 
     * @return string|null
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * 
     * @return void
     */
    public function setSku(string $sku = null)
    {
        $this->sku = StringUtil::trimToNull($sku, 255);
    }

    /**
     * 
     * @return int|null
     */
    public function getOnHold()
    {
        return $this->onHold;
    }

    /**
     * @param int $onHold
     * 
     * @return void
     */
    public function setOnHold(int $onHold = null)
    {
        $this->onHold = $onHold;
    }

    /**
     * 
     * @return int|null
     */
    public function getOnHand()
    {
        return $this->onHand;
    }

    /**
     * @param int $onHand
     * 
     * @return void
     */
    public function setOnHand(int $onHand = null)
    {
        $this->onHand = $onHand;
    }

    /**
     * 
     * @return int|null
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * @param int $sold
     * 
     * @return void
     */
    public function setSold(int $sold = null)
    {
        $this->sold = $sold;
    }

    /**
     * 
     * @return string|null
     */
    public function getAvailableOnDemand()
    {
        return $this->availableOnDemand;
    }

    /**
     * @param string $availableOnDemand
     * 
     * @return void
     */
    public function setAvailableOnDemand(string $availableOnDemand = null)
    {
        $this->availableOnDemand = StringUtil::trimToNull($availableOnDemand, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     * 
     * @return void
     */
    public function setPrice(int $price = null)
    {
        $this->price = $price;
    }

    /**
     * 
     * @return string|null
     */
    public function getPricingCalculator()
    {
        return $this->pricingCalculator;
    }

    /**
     * @param string $pricingCalculator
     * 
     * @return void
     */
    public function setPricingCalculator(string $pricingCalculator = null)
    {
        $this->pricingCalculator = StringUtil::trimToNull($pricingCalculator, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPricingConfiguration()
    {
        return $this->pricingConfiguration;
    }

    /**
     * @param string $pricingConfiguration
     * 
     * @return void
     */
    public function setPricingConfiguration(string $pricingConfiguration = null)
    {
        $this->pricingConfiguration = StringUtil::trimToNull($pricingConfiguration, null);
    }

    /**
     * 
     * @return float|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $width
     * 
     * @return void
     */
    public function setWidth(float $width = null)
    {
        $this->width = $width;
    }

    /**
     * 
     * @return float|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     * 
     * @return void
     */
    public function setHeight(float $height = null)
    {
        $this->height = $height;
    }

    /**
     * 
     * @return float|null
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param float $depth
     * 
     * @return void
     */
    public function setDepth(float $depth = null)
    {
        $this->depth = $depth;
    }

    /**
     * 
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * 
     * @return void
     */
    public function setWeight(float $weight = null)
    {
        $this->weight = $weight;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProduct|null
     */
    public function getA29B5234584665A(bool $forceReload = false)
    {
        if ($this->A29B5234584665A === null || $forceReload) {
            $this->A29B5234584665A = SyliusProductService::getInstance()->getEntityByPrimaryKey($this->productId);
        }
        return $this->A29B5234584665A;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return void
     */
    public function setA29B5234584665A(SyliusProduct $entity = null)
    {
        $this->A29B5234584665A = $entity;
        $this->productId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductVariant $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductVariant $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}