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

class sylius_product_variant implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_variant";

    const COLUMN_ID = "id";

    const COLUMN_PRODUCT_ID = "product_id";

    const COLUMN_IS_MASTER = "is_master";

    const COLUMN_PRESENTATION = "presentation";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

    const COLUMN_AVAILABLE_ON = "available_on";

    const COLUMN_SKU = "sku";

    const COLUMN_ON_HOLD = "on_hold";

    const COLUMN_ON_HAND = "on_hand";

    const COLUMN_SOLD = "sold";

    const COLUMN_AVAILABLE_ON_DEMAND = "available_on_demand";

    const COLUMN_PRICE = "price";

    const COLUMN_PRICING_CALCULATOR = "pricing_calculator";

    const COLUMN_PRICING_CONFIGURATION = "pricing_configuration";

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
    protected $product_id;

    /**
     * @var string
     */
    protected $is_master;

    /**
     * @var string
     */
    protected $presentation;

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
    protected $available_on;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var int
     */
    protected $on_hold;

    /**
     * @var int
     */
    protected $on_hand;

    /**
     * @var int
     */
    protected $sold;

    /**
     * @var string
     */
    protected $available_on_demand;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var string
     */
    protected $pricing_calculator;

    /**
     * @var string
     */
    protected $pricing_configuration;

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
     * @var sylius_product
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->product_id) . ',' . Escaper::quoteString($connection, $this->is_master) . ',' . Escaper::quoteString($connection, $this->presentation) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ',' . Escaper::quoteDateTime($this->available_on) . ',' . Escaper::quoteString($connection, $this->sku) . ',' . Escaper::quoteInt($this->on_hold) . ',' . Escaper::quoteInt($this->on_hand) . ',' . Escaper::quoteInt($this->sold) . ',' . Escaper::quoteString($connection, $this->available_on_demand) . ',' . Escaper::quoteInt($this->price) . ',' . Escaper::quoteString($connection, $this->pricing_calculator) . ',' . Escaper::quoteString($connection, $this->pricing_configuration) . ',' . Escaper::quoteFloat($this->width) . ',' . Escaper::quoteFloat($this->height) . ',' . Escaper::quoteFloat($this->depth) . ',' . Escaper::quoteFloat($this->weight) . ');';
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
        $this->product_id = $resultSet->getIntegerValue("product_id");
        $this->is_master = $resultSet->getStringValue("is_master");
        $this->presentation = $resultSet->getStringValue("presentation");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
        $this->available_on = $resultSet->getDateTime("available_on");
        $this->sku = $resultSet->getStringValue("sku");
        $this->on_hold = $resultSet->getIntegerValue("on_hold");
        $this->on_hand = $resultSet->getIntegerValue("on_hand");
        $this->sold = $resultSet->getIntegerValue("sold");
        $this->available_on_demand = $resultSet->getStringValue("available_on_demand");
        $this->price = $resultSet->getIntegerValue("price");
        $this->pricing_calculator = $resultSet->getStringValue("pricing_calculator");
        $this->pricing_configuration = $resultSet->getStringValue("pricing_configuration");
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
        $this->setProduct_id($arrayAccessor->getIntegerValue("product_id"));
        $this->setIs_master($arrayAccessor->getStringValue("is_master"));
        $this->setPresentation($arrayAccessor->getStringValue("presentation"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->setAvailable_on($arrayAccessor->getDateTime("available_on"));
        $this->setSku($arrayAccessor->getStringValue("sku"));
        $this->setOn_hold($arrayAccessor->getIntegerValue("on_hold"));
        $this->setOn_hand($arrayAccessor->getIntegerValue("on_hand"));
        $this->setSold($arrayAccessor->getIntegerValue("sold"));
        $this->setAvailable_on_demand($arrayAccessor->getStringValue("available_on_demand"));
        $this->setPrice($arrayAccessor->getIntegerValue("price"));
        $this->setPricing_calculator($arrayAccessor->getStringValue("pricing_calculator"));
        $this->setPricing_configuration($arrayAccessor->getStringValue("pricing_configuration"));
        $this->setWidth($arrayAccessor->getFloatValue("width"));
        $this->setHeight($arrayAccessor->getFloatValue("height"));
        $this->setDepth($arrayAccessor->getFloatValue("depth"));
        $this->setWeight($arrayAccessor->getFloatValue("weight"));
        $this->_existing = ($this->id !== null);
        $A29B5234584665AArray = $arrayAccessor->getArray("A29B5234584665A");
        if ($A29B5234584665AArray !== null) {
            $A29B5234584665A = sylius_productService::getInstance()->newInstance();
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
            "product_id" => $this->getProduct_id(),
            "is_master" => $this->getIs_master(),
            "presentation" => $this->getPresentation(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null,
            "available_on" => ($this->getAvailable_on() !== null) ? $this->getAvailable_on()->getJSONDateTime() : null,
            "sku" => $this->getSku(),
            "on_hold" => $this->getOn_hold(),
            "on_hand" => $this->getOn_hand(),
            "sold" => $this->getSold(),
            "available_on_demand" => $this->getAvailable_on_demand(),
            "price" => $this->getPrice(),
            "pricing_calculator" => $this->getPricing_calculator(),
            "pricing_configuration" => $this->getPricing_configuration(),
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
    public function getProduct_id()
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     * 
     * @return void
     */
    public function setProduct_id(int $product_id = null)
    {
        $this->product_id = $product_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getIs_master()
    {
        return $this->is_master;
    }

    /**
     * @param string $is_master
     * 
     * @return void
     */
    public function setIs_master(string $is_master = null)
    {
        $this->is_master = StringUtil::trimToNull($is_master, null);
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
    public function getAvailable_on()
    {
        return $this->available_on;
    }

    /**
     * @param SiestaDateTime $available_on
     * 
     * @return void
     */
    public function setAvailable_on(SiestaDateTime $available_on = null)
    {
        $this->available_on = $available_on;
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
    public function getOn_hold()
    {
        return $this->on_hold;
    }

    /**
     * @param int $on_hold
     * 
     * @return void
     */
    public function setOn_hold(int $on_hold = null)
    {
        $this->on_hold = $on_hold;
    }

    /**
     * 
     * @return int|null
     */
    public function getOn_hand()
    {
        return $this->on_hand;
    }

    /**
     * @param int $on_hand
     * 
     * @return void
     */
    public function setOn_hand(int $on_hand = null)
    {
        $this->on_hand = $on_hand;
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
    public function getAvailable_on_demand()
    {
        return $this->available_on_demand;
    }

    /**
     * @param string $available_on_demand
     * 
     * @return void
     */
    public function setAvailable_on_demand(string $available_on_demand = null)
    {
        $this->available_on_demand = StringUtil::trimToNull($available_on_demand, null);
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
    public function getPricing_calculator()
    {
        return $this->pricing_calculator;
    }

    /**
     * @param string $pricing_calculator
     * 
     * @return void
     */
    public function setPricing_calculator(string $pricing_calculator = null)
    {
        $this->pricing_calculator = StringUtil::trimToNull($pricing_calculator, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPricing_configuration()
    {
        return $this->pricing_configuration;
    }

    /**
     * @param string $pricing_configuration
     * 
     * @return void
     */
    public function setPricing_configuration(string $pricing_configuration = null)
    {
        $this->pricing_configuration = StringUtil::trimToNull($pricing_configuration, null);
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
     * @return sylius_product|null
     */
    public function getA29B5234584665A(bool $forceReload = false)
    {
        if ($this->A29B5234584665A === null || $forceReload) {
            $this->A29B5234584665A = sylius_productService::getInstance()->getEntityByPrimaryKey($this->product_id);
        }
        return $this->A29B5234584665A;
    }

    /**
     * @param sylius_product $entity
     * 
     * @return void
     */
    public function setA29B5234584665A(sylius_product $entity = null)
    {
        $this->A29B5234584665A = $entity;
        $this->product_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_variant $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_variant $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}