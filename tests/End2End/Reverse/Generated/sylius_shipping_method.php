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

class sylius_shipping_method implements ArraySerializable
{

    const TABLE_NAME = "sylius_shipping_method";

    const COLUMN_ID = "id";

    const COLUMN_CATEGORY_ID = "category_id";

    const COLUMN_ZONE_ID = "zone_id";

    const COLUMN_CONFIGURATION = "configuration";

    const COLUMN_CATEGORY_REQUIREMENT = "category_requirement";

    const COLUMN_CALCULATOR = "calculator";

    const COLUMN_IS_ENABLED = "is_enabled";

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
    protected $category_id;

    /**
     * @var int
     */
    protected $zone_id;

    /**
     * @var string
     */
    protected $configuration;

    /**
     * @var int
     */
    protected $category_requirement;

    /**
     * @var string
     */
    protected $calculator;

    /**
     * @var string
     */
    protected $is_enabled;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_shipping_category
     */
    protected $5FB0EE1112469DE2;

    /**
     * @var sylius_zone
     */
    protected $5FB0EE119F2C3FAB;

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
        $spCall = ($this->_existing) ? "CALL sylius_shipping_method_U(" : "CALL sylius_shipping_method_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->category_id) . ',' . Escaper::quoteInt($this->zone_id) . ',' . Escaper::quoteString($connection, $this->configuration) . ',' . Escaper::quoteInt($this->category_requirement) . ',' . Escaper::quoteString($connection, $this->calculator) . ',' . Escaper::quoteString($connection, $this->is_enabled) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        if ($cascade && $this->5FB0EE1112469DE2 !== null) {
            $this->5FB0EE1112469DE2->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->5FB0EE119F2C3FAB !== null) {
            $this->5FB0EE119F2C3FAB->save($cascade, $cycleDetector, $connectionName);
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
        $this->category_id = $resultSet->getIntegerValue("category_id");
        $this->zone_id = $resultSet->getIntegerValue("zone_id");
        $this->configuration = $resultSet->getStringValue("configuration");
        $this->category_requirement = $resultSet->getIntegerValue("category_requirement");
        $this->calculator = $resultSet->getStringValue("calculator");
        $this->is_enabled = $resultSet->getStringValue("is_enabled");
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
        $connection->execute("CALL sylius_shipping_method_DB_PK($id)");
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
        $this->setCategory_id($arrayAccessor->getIntegerValue("category_id"));
        $this->setZone_id($arrayAccessor->getIntegerValue("zone_id"));
        $this->setConfiguration($arrayAccessor->getStringValue("configuration"));
        $this->setCategory_requirement($arrayAccessor->getIntegerValue("category_requirement"));
        $this->setCalculator($arrayAccessor->getStringValue("calculator"));
        $this->setIs_enabled($arrayAccessor->getStringValue("is_enabled"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $5FB0EE1112469DE2Array = $arrayAccessor->getArray("5FB0EE1112469DE2");
        if ($5FB0EE1112469DE2Array !== null) {
            $5FB0EE1112469DE2 = sylius_shipping_categoryService::getInstance()->newInstance();
            $5FB0EE1112469DE2->fromArray($5FB0EE1112469DE2Array);
            $this->set5FB0EE1112469DE2($5FB0EE1112469DE2);
        }
        $5FB0EE119F2C3FABArray = $arrayAccessor->getArray("5FB0EE119F2C3FAB");
        if ($5FB0EE119F2C3FABArray !== null) {
            $5FB0EE119F2C3FAB = sylius_zoneService::getInstance()->newInstance();
            $5FB0EE119F2C3FAB->fromArray($5FB0EE119F2C3FABArray);
            $this->set5FB0EE119F2C3FAB($5FB0EE119F2C3FAB);
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
            "category_id" => $this->getCategory_id(),
            "zone_id" => $this->getZone_id(),
            "configuration" => $this->getConfiguration(),
            "category_requirement" => $this->getCategory_requirement(),
            "calculator" => $this->getCalculator(),
            "is_enabled" => $this->getIs_enabled(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
        ];
        if ($this->5FB0EE1112469DE2 !== null) {
            $result["5FB0EE1112469DE2"] = $this->5FB0EE1112469DE2->toArray($cycleDetector);
        }
        if ($this->5FB0EE119F2C3FAB !== null) {
            $result["5FB0EE119F2C3FAB"] = $this->5FB0EE119F2C3FAB->toArray($cycleDetector);
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
    public function getCategory_id()
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     * 
     * @return void
     */
    public function setCategory_id(int $category_id = null)
    {
        $this->category_id = $category_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getZone_id()
    {
        return $this->zone_id;
    }

    /**
     * @param int $zone_id
     * 
     * @return void
     */
    public function setZone_id(int $zone_id = null)
    {
        $this->zone_id = $zone_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param string $configuration
     * 
     * @return void
     */
    public function setConfiguration(string $configuration = null)
    {
        $this->configuration = StringUtil::trimToNull($configuration, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getCategory_requirement()
    {
        return $this->category_requirement;
    }

    /**
     * @param int $category_requirement
     * 
     * @return void
     */
    public function setCategory_requirement(int $category_requirement = null)
    {
        $this->category_requirement = $category_requirement;
    }

    /**
     * 
     * @return string|null
     */
    public function getCalculator()
    {
        return $this->calculator;
    }

    /**
     * @param string $calculator
     * 
     * @return void
     */
    public function setCalculator(string $calculator = null)
    {
        $this->calculator = StringUtil::trimToNull($calculator, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getIs_enabled()
    {
        return $this->is_enabled;
    }

    /**
     * @param string $is_enabled
     * 
     * @return void
     */
    public function setIs_enabled(string $is_enabled = null)
    {
        $this->is_enabled = StringUtil::trimToNull($is_enabled, null);
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
     * @return sylius_shipping_category|null
     */
    public function get5FB0EE1112469DE2(bool $forceReload = false)
    {
        if ($this->5FB0EE1112469DE2 === null || $forceReload) {
            $this->5FB0EE1112469DE2 = sylius_shipping_categoryService::getInstance()->getEntityByPrimaryKey($this->category_id);
        }
        return $this->5FB0EE1112469DE2;
    }

    /**
     * @param sylius_shipping_category $entity
     * 
     * @return void
     */
    public function set5FB0EE1112469DE2(sylius_shipping_category $entity = null)
    {
        $this->5FB0EE1112469DE2 = $entity;
        $this->category_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_zone|null
     */
    public function get5FB0EE119F2C3FAB(bool $forceReload = false)
    {
        if ($this->5FB0EE119F2C3FAB === null || $forceReload) {
            $this->5FB0EE119F2C3FAB = sylius_zoneService::getInstance()->getEntityByPrimaryKey($this->zone_id);
        }
        return $this->5FB0EE119F2C3FAB;
    }

    /**
     * @param sylius_zone $entity
     * 
     * @return void
     */
    public function set5FB0EE119F2C3FAB(sylius_zone $entity = null)
    {
        $this->5FB0EE119F2C3FAB = $entity;
        $this->zone_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_shipping_method $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_shipping_method $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}