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

class sylius_product implements ArraySerializable
{

    const TABLE_NAME = "sylius_product";

    const COLUMN_ID = "id";

    const COLUMN_ARCHETYPE_ID = "archetype_id";

    const COLUMN_TAX_CATEGORY_ID = "tax_category_id";

    const COLUMN_SHIPPING_CATEGORY_ID = "shipping_category_id";

    const COLUMN_RESTRICTED_ZONE = "restricted_zone";

    const COLUMN_AVAILABLE_ON = "available_on";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

    const COLUMN_VARIANT_SELECTION_METHOD = "variant_selection_method";

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
    protected $archetype_id;

    /**
     * @var int
     */
    protected $tax_category_id;

    /**
     * @var int
     */
    protected $shipping_category_id;

    /**
     * @var int
     */
    protected $restricted_zone;

    /**
     * @var SiestaDateTime
     */
    protected $available_on;

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
     * @var string
     */
    protected $variant_selection_method;

    /**
     * @var sylius_product_archetype
     */
    protected $677B9B74732C6CC7;

    /**
     * @var sylius_tax_category
     */
    protected $677B9B749DF894ED;

    /**
     * @var sylius_shipping_category
     */
    protected $677B9B749E2D1A41;

    /**
     * @var sylius_zone
     */
    protected $677B9B74E64AACD3;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_U(" : "CALL sylius_product_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->archetype_id) . ',' . Escaper::quoteInt($this->tax_category_id) . ',' . Escaper::quoteInt($this->shipping_category_id) . ',' . Escaper::quoteInt($this->restricted_zone) . ',' . Escaper::quoteDateTime($this->available_on) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ',' . Escaper::quoteString($connection, $this->variant_selection_method) . ');';
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
        if ($cascade && $this->677B9B74732C6CC7 !== null) {
            $this->677B9B74732C6CC7->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->677B9B749DF894ED !== null) {
            $this->677B9B749DF894ED->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->677B9B749E2D1A41 !== null) {
            $this->677B9B749E2D1A41->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->677B9B74E64AACD3 !== null) {
            $this->677B9B74E64AACD3->save($cascade, $cycleDetector, $connectionName);
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
        $this->archetype_id = $resultSet->getIntegerValue("archetype_id");
        $this->tax_category_id = $resultSet->getIntegerValue("tax_category_id");
        $this->shipping_category_id = $resultSet->getIntegerValue("shipping_category_id");
        $this->restricted_zone = $resultSet->getIntegerValue("restricted_zone");
        $this->available_on = $resultSet->getDateTime("available_on");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
        $this->variant_selection_method = $resultSet->getStringValue("variant_selection_method");
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
        $connection->execute("CALL sylius_product_DB_PK($id)");
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
        $this->setArchetype_id($arrayAccessor->getIntegerValue("archetype_id"));
        $this->setTax_category_id($arrayAccessor->getIntegerValue("tax_category_id"));
        $this->setShipping_category_id($arrayAccessor->getIntegerValue("shipping_category_id"));
        $this->setRestricted_zone($arrayAccessor->getIntegerValue("restricted_zone"));
        $this->setAvailable_on($arrayAccessor->getDateTime("available_on"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->setVariant_selection_method($arrayAccessor->getStringValue("variant_selection_method"));
        $this->_existing = ($this->id !== null);
        $677B9B74732C6CC7Array = $arrayAccessor->getArray("677B9B74732C6CC7");
        if ($677B9B74732C6CC7Array !== null) {
            $677B9B74732C6CC7 = sylius_product_archetypeService::getInstance()->newInstance();
            $677B9B74732C6CC7->fromArray($677B9B74732C6CC7Array);
            $this->set677B9B74732C6CC7($677B9B74732C6CC7);
        }
        $677B9B749DF894EDArray = $arrayAccessor->getArray("677B9B749DF894ED");
        if ($677B9B749DF894EDArray !== null) {
            $677B9B749DF894ED = sylius_tax_categoryService::getInstance()->newInstance();
            $677B9B749DF894ED->fromArray($677B9B749DF894EDArray);
            $this->set677B9B749DF894ED($677B9B749DF894ED);
        }
        $677B9B749E2D1A41Array = $arrayAccessor->getArray("677B9B749E2D1A41");
        if ($677B9B749E2D1A41Array !== null) {
            $677B9B749E2D1A41 = sylius_shipping_categoryService::getInstance()->newInstance();
            $677B9B749E2D1A41->fromArray($677B9B749E2D1A41Array);
            $this->set677B9B749E2D1A41($677B9B749E2D1A41);
        }
        $677B9B74E64AACD3Array = $arrayAccessor->getArray("677B9B74E64AACD3");
        if ($677B9B74E64AACD3Array !== null) {
            $677B9B74E64AACD3 = sylius_zoneService::getInstance()->newInstance();
            $677B9B74E64AACD3->fromArray($677B9B74E64AACD3Array);
            $this->set677B9B74E64AACD3($677B9B74E64AACD3);
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
            "archetype_id" => $this->getArchetype_id(),
            "tax_category_id" => $this->getTax_category_id(),
            "shipping_category_id" => $this->getShipping_category_id(),
            "restricted_zone" => $this->getRestricted_zone(),
            "available_on" => ($this->getAvailable_on() !== null) ? $this->getAvailable_on()->getJSONDateTime() : null,
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null,
            "variant_selection_method" => $this->getVariant_selection_method()
        ];
        if ($this->677B9B74732C6CC7 !== null) {
            $result["677B9B74732C6CC7"] = $this->677B9B74732C6CC7->toArray($cycleDetector);
        }
        if ($this->677B9B749DF894ED !== null) {
            $result["677B9B749DF894ED"] = $this->677B9B749DF894ED->toArray($cycleDetector);
        }
        if ($this->677B9B749E2D1A41 !== null) {
            $result["677B9B749E2D1A41"] = $this->677B9B749E2D1A41->toArray($cycleDetector);
        }
        if ($this->677B9B74E64AACD3 !== null) {
            $result["677B9B74E64AACD3"] = $this->677B9B74E64AACD3->toArray($cycleDetector);
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
    public function getArchetype_id()
    {
        return $this->archetype_id;
    }

    /**
     * @param int $archetype_id
     * 
     * @return void
     */
    public function setArchetype_id(int $archetype_id = null)
    {
        $this->archetype_id = $archetype_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getTax_category_id()
    {
        return $this->tax_category_id;
    }

    /**
     * @param int $tax_category_id
     * 
     * @return void
     */
    public function setTax_category_id(int $tax_category_id = null)
    {
        $this->tax_category_id = $tax_category_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getShipping_category_id()
    {
        return $this->shipping_category_id;
    }

    /**
     * @param int $shipping_category_id
     * 
     * @return void
     */
    public function setShipping_category_id(int $shipping_category_id = null)
    {
        $this->shipping_category_id = $shipping_category_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getRestricted_zone()
    {
        return $this->restricted_zone;
    }

    /**
     * @param int $restricted_zone
     * 
     * @return void
     */
    public function setRestricted_zone(int $restricted_zone = null)
    {
        $this->restricted_zone = $restricted_zone;
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
     * @return string|null
     */
    public function getVariant_selection_method()
    {
        return $this->variant_selection_method;
    }

    /**
     * @param string $variant_selection_method
     * 
     * @return void
     */
    public function setVariant_selection_method(string $variant_selection_method = null)
    {
        $this->variant_selection_method = StringUtil::trimToNull($variant_selection_method, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_archetype|null
     */
    public function get677B9B74732C6CC7(bool $forceReload = false)
    {
        if ($this->677B9B74732C6CC7 === null || $forceReload) {
            $this->677B9B74732C6CC7 = sylius_product_archetypeService::getInstance()->getEntityByPrimaryKey($this->archetype_id);
        }
        return $this->677B9B74732C6CC7;
    }

    /**
     * @param sylius_product_archetype $entity
     * 
     * @return void
     */
    public function set677B9B74732C6CC7(sylius_product_archetype $entity = null)
    {
        $this->677B9B74732C6CC7 = $entity;
        $this->archetype_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_tax_category|null
     */
    public function get677B9B749DF894ED(bool $forceReload = false)
    {
        if ($this->677B9B749DF894ED === null || $forceReload) {
            $this->677B9B749DF894ED = sylius_tax_categoryService::getInstance()->getEntityByPrimaryKey($this->tax_category_id);
        }
        return $this->677B9B749DF894ED;
    }

    /**
     * @param sylius_tax_category $entity
     * 
     * @return void
     */
    public function set677B9B749DF894ED(sylius_tax_category $entity = null)
    {
        $this->677B9B749DF894ED = $entity;
        $this->tax_category_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_shipping_category|null
     */
    public function get677B9B749E2D1A41(bool $forceReload = false)
    {
        if ($this->677B9B749E2D1A41 === null || $forceReload) {
            $this->677B9B749E2D1A41 = sylius_shipping_categoryService::getInstance()->getEntityByPrimaryKey($this->shipping_category_id);
        }
        return $this->677B9B749E2D1A41;
    }

    /**
     * @param sylius_shipping_category $entity
     * 
     * @return void
     */
    public function set677B9B749E2D1A41(sylius_shipping_category $entity = null)
    {
        $this->677B9B749E2D1A41 = $entity;
        $this->shipping_category_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_zone|null
     */
    public function get677B9B74E64AACD3(bool $forceReload = false)
    {
        if ($this->677B9B74E64AACD3 === null || $forceReload) {
            $this->677B9B74E64AACD3 = sylius_zoneService::getInstance()->getEntityByPrimaryKey($this->restricted_zone);
        }
        return $this->677B9B74E64AACD3;
    }

    /**
     * @param sylius_zone $entity
     * 
     * @return void
     */
    public function set677B9B74E64AACD3(sylius_zone $entity = null)
    {
        $this->677B9B74E64AACD3 = $entity;
        $this->restricted_zone = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}