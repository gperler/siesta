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

class SyliusProduct implements ArraySerializable
{

    const TABLE_NAME = "sylius_product";

    const COLUMN_ID = "id";

    const COLUMN_ARCHETYPEID = "archetype_id";

    const COLUMN_TAXCATEGORYID = "tax_category_id";

    const COLUMN_SHIPPINGCATEGORYID = "shipping_category_id";

    const COLUMN_RESTRICTEDZONE = "restricted_zone";

    const COLUMN_AVAILABLEON = "available_on";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

    const COLUMN_VARIANTSELECTIONMETHOD = "variant_selection_method";

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
    protected $archetypeId;

    /**
     * @var int
     */
    protected $taxCategoryId;

    /**
     * @var int
     */
    protected $shippingCategoryId;

    /**
     * @var int
     */
    protected $restrictedZone;

    /**
     * @var SiestaDateTime
     */
    protected $availableOn;

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
     * @var string
     */
    protected $variantSelectionMethod;

    /**
     * @var SyliusProductArchetype
     */
    protected $677B9B74732C6CC7;

    /**
     * @var SyliusTaxCategory
     */
    protected $677B9B749DF894ED;

    /**
     * @var SyliusShippingCategory
     */
    protected $677B9B749E2D1A41;

    /**
     * @var SyliusZone
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->archetypeId) . ',' . Escaper::quoteInt($this->taxCategoryId) . ',' . Escaper::quoteInt($this->shippingCategoryId) . ',' . Escaper::quoteInt($this->restrictedZone) . ',' . Escaper::quoteDateTime($this->availableOn) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ',' . Escaper::quoteString($connection, $this->variantSelectionMethod) . ');';
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
        $this->archetypeId = $resultSet->getIntegerValue("archetype_id");
        $this->taxCategoryId = $resultSet->getIntegerValue("tax_category_id");
        $this->shippingCategoryId = $resultSet->getIntegerValue("shipping_category_id");
        $this->restrictedZone = $resultSet->getIntegerValue("restricted_zone");
        $this->availableOn = $resultSet->getDateTime("available_on");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
        $this->variantSelectionMethod = $resultSet->getStringValue("variant_selection_method");
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
        $this->setArchetypeId($arrayAccessor->getIntegerValue("archetypeId"));
        $this->setTaxCategoryId($arrayAccessor->getIntegerValue("taxCategoryId"));
        $this->setShippingCategoryId($arrayAccessor->getIntegerValue("shippingCategoryId"));
        $this->setRestrictedZone($arrayAccessor->getIntegerValue("restrictedZone"));
        $this->setAvailableOn($arrayAccessor->getDateTime("availableOn"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->setVariantSelectionMethod($arrayAccessor->getStringValue("variantSelectionMethod"));
        $this->_existing = ($this->id !== null);
        $677B9B74732C6CC7Array = $arrayAccessor->getArray("677B9B74732C6CC7");
        if ($677B9B74732C6CC7Array !== null) {
            $677B9B74732C6CC7 = SyliusProductArchetypeService::getInstance()->newInstance();
            $677B9B74732C6CC7->fromArray($677B9B74732C6CC7Array);
            $this->set677B9B74732C6CC7($677B9B74732C6CC7);
        }
        $677B9B749DF894EDArray = $arrayAccessor->getArray("677B9B749DF894ED");
        if ($677B9B749DF894EDArray !== null) {
            $677B9B749DF894ED = SyliusTaxCategoryService::getInstance()->newInstance();
            $677B9B749DF894ED->fromArray($677B9B749DF894EDArray);
            $this->set677B9B749DF894ED($677B9B749DF894ED);
        }
        $677B9B749E2D1A41Array = $arrayAccessor->getArray("677B9B749E2D1A41");
        if ($677B9B749E2D1A41Array !== null) {
            $677B9B749E2D1A41 = SyliusShippingCategoryService::getInstance()->newInstance();
            $677B9B749E2D1A41->fromArray($677B9B749E2D1A41Array);
            $this->set677B9B749E2D1A41($677B9B749E2D1A41);
        }
        $677B9B74E64AACD3Array = $arrayAccessor->getArray("677B9B74E64AACD3");
        if ($677B9B74E64AACD3Array !== null) {
            $677B9B74E64AACD3 = SyliusZoneService::getInstance()->newInstance();
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
            "archetypeId" => $this->getArchetypeId(),
            "taxCategoryId" => $this->getTaxCategoryId(),
            "shippingCategoryId" => $this->getShippingCategoryId(),
            "restrictedZone" => $this->getRestrictedZone(),
            "availableOn" => ($this->getAvailableOn() !== null) ? $this->getAvailableOn()->getJSONDateTime() : null,
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null,
            "variantSelectionMethod" => $this->getVariantSelectionMethod()
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
    public function getArchetypeId()
    {
        return $this->archetypeId;
    }

    /**
     * @param int $archetypeId
     * 
     * @return void
     */
    public function setArchetypeId(int $archetypeId = null)
    {
        $this->archetypeId = $archetypeId;
    }

    /**
     * 
     * @return int|null
     */
    public function getTaxCategoryId()
    {
        return $this->taxCategoryId;
    }

    /**
     * @param int $taxCategoryId
     * 
     * @return void
     */
    public function setTaxCategoryId(int $taxCategoryId = null)
    {
        $this->taxCategoryId = $taxCategoryId;
    }

    /**
     * 
     * @return int|null
     */
    public function getShippingCategoryId()
    {
        return $this->shippingCategoryId;
    }

    /**
     * @param int $shippingCategoryId
     * 
     * @return void
     */
    public function setShippingCategoryId(int $shippingCategoryId = null)
    {
        $this->shippingCategoryId = $shippingCategoryId;
    }

    /**
     * 
     * @return int|null
     */
    public function getRestrictedZone()
    {
        return $this->restrictedZone;
    }

    /**
     * @param int $restrictedZone
     * 
     * @return void
     */
    public function setRestrictedZone(int $restrictedZone = null)
    {
        $this->restrictedZone = $restrictedZone;
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
     * @return string|null
     */
    public function getVariantSelectionMethod()
    {
        return $this->variantSelectionMethod;
    }

    /**
     * @param string $variantSelectionMethod
     * 
     * @return void
     */
    public function setVariantSelectionMethod(string $variantSelectionMethod = null)
    {
        $this->variantSelectionMethod = StringUtil::trimToNull($variantSelectionMethod, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductArchetype|null
     */
    public function get677B9B74732C6CC7(bool $forceReload = false)
    {
        if ($this->677B9B74732C6CC7 === null || $forceReload) {
            $this->677B9B74732C6CC7 = SyliusProductArchetypeService::getInstance()->getEntityByPrimaryKey($this->archetypeId);
        }
        return $this->677B9B74732C6CC7;
    }

    /**
     * @param SyliusProductArchetype $entity
     * 
     * @return void
     */
    public function set677B9B74732C6CC7(SyliusProductArchetype $entity = null)
    {
        $this->677B9B74732C6CC7 = $entity;
        $this->archetypeId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusTaxCategory|null
     */
    public function get677B9B749DF894ED(bool $forceReload = false)
    {
        if ($this->677B9B749DF894ED === null || $forceReload) {
            $this->677B9B749DF894ED = SyliusTaxCategoryService::getInstance()->getEntityByPrimaryKey($this->taxCategoryId);
        }
        return $this->677B9B749DF894ED;
    }

    /**
     * @param SyliusTaxCategory $entity
     * 
     * @return void
     */
    public function set677B9B749DF894ED(SyliusTaxCategory $entity = null)
    {
        $this->677B9B749DF894ED = $entity;
        $this->taxCategoryId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusShippingCategory|null
     */
    public function get677B9B749E2D1A41(bool $forceReload = false)
    {
        if ($this->677B9B749E2D1A41 === null || $forceReload) {
            $this->677B9B749E2D1A41 = SyliusShippingCategoryService::getInstance()->getEntityByPrimaryKey($this->shippingCategoryId);
        }
        return $this->677B9B749E2D1A41;
    }

    /**
     * @param SyliusShippingCategory $entity
     * 
     * @return void
     */
    public function set677B9B749E2D1A41(SyliusShippingCategory $entity = null)
    {
        $this->677B9B749E2D1A41 = $entity;
        $this->shippingCategoryId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusZone|null
     */
    public function get677B9B74E64AACD3(bool $forceReload = false)
    {
        if ($this->677B9B74E64AACD3 === null || $forceReload) {
            $this->677B9B74E64AACD3 = SyliusZoneService::getInstance()->getEntityByPrimaryKey($this->restrictedZone);
        }
        return $this->677B9B74E64AACD3;
    }

    /**
     * @param SyliusZone $entity
     * 
     * @return void
     */
    public function set677B9B74E64AACD3(SyliusZone $entity = null)
    {
        $this->677B9B74E64AACD3 = $entity;
        $this->restrictedZone = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProduct $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}