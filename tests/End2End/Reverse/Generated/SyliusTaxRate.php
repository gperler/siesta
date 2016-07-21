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

class SyliusTaxRate implements ArraySerializable
{

    const TABLE_NAME = "sylius_tax_rate";

    const COLUMN_ID = "id";

    const COLUMN_CATEGORYID = "category_id";

    const COLUMN_ZONEID = "zone_id";

    const COLUMN_NAME = "name";

    const COLUMN_AMOUNT = "amount";

    const COLUMN_INCLUDEDINPRICE = "included_in_price";

    const COLUMN_CALCULATOR = "calculator";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

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
    protected $categoryId;

    /**
     * @var int
     */
    protected $zoneId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $includedInPrice;

    /**
     * @var string
     */
    protected $calculator;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusTaxCategory
     */
    protected $3CD86B2E12469DE2;

    /**
     * @var SyliusZone
     */
    protected $3CD86B2E9F2C3FAB;

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
        $spCall = ($this->_existing) ? "CALL sylius_tax_rate_U(" : "CALL sylius_tax_rate_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->categoryId) . ',' . Escaper::quoteInt($this->zoneId) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteFloat($this->amount) . ',' . Escaper::quoteString($connection, $this->includedInPrice) . ',' . Escaper::quoteString($connection, $this->calculator) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        if ($cascade && $this->3CD86B2E12469DE2 !== null) {
            $this->3CD86B2E12469DE2->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->3CD86B2E9F2C3FAB !== null) {
            $this->3CD86B2E9F2C3FAB->save($cascade, $cycleDetector, $connectionName);
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
        $this->categoryId = $resultSet->getIntegerValue("category_id");
        $this->zoneId = $resultSet->getIntegerValue("zone_id");
        $this->name = $resultSet->getStringValue("name");
        $this->amount = $resultSet->getFloatValue("amount");
        $this->includedInPrice = $resultSet->getStringValue("included_in_price");
        $this->calculator = $resultSet->getStringValue("calculator");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
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
        $connection->execute("CALL sylius_tax_rate_DB_PK($id)");
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
        $this->setCategoryId($arrayAccessor->getIntegerValue("categoryId"));
        $this->setZoneId($arrayAccessor->getIntegerValue("zoneId"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setAmount($arrayAccessor->getFloatValue("amount"));
        $this->setIncludedInPrice($arrayAccessor->getStringValue("includedInPrice"));
        $this->setCalculator($arrayAccessor->getStringValue("calculator"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $3CD86B2E12469DE2Array = $arrayAccessor->getArray("3CD86B2E12469DE2");
        if ($3CD86B2E12469DE2Array !== null) {
            $3CD86B2E12469DE2 = SyliusTaxCategoryService::getInstance()->newInstance();
            $3CD86B2E12469DE2->fromArray($3CD86B2E12469DE2Array);
            $this->set3CD86B2E12469DE2($3CD86B2E12469DE2);
        }
        $3CD86B2E9F2C3FABArray = $arrayAccessor->getArray("3CD86B2E9F2C3FAB");
        if ($3CD86B2E9F2C3FABArray !== null) {
            $3CD86B2E9F2C3FAB = SyliusZoneService::getInstance()->newInstance();
            $3CD86B2E9F2C3FAB->fromArray($3CD86B2E9F2C3FABArray);
            $this->set3CD86B2E9F2C3FAB($3CD86B2E9F2C3FAB);
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
            "categoryId" => $this->getCategoryId(),
            "zoneId" => $this->getZoneId(),
            "name" => $this->getName(),
            "amount" => $this->getAmount(),
            "includedInPrice" => $this->getIncludedInPrice(),
            "calculator" => $this->getCalculator(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
        ];
        if ($this->3CD86B2E12469DE2 !== null) {
            $result["3CD86B2E12469DE2"] = $this->3CD86B2E12469DE2->toArray($cycleDetector);
        }
        if ($this->3CD86B2E9F2C3FAB !== null) {
            $result["3CD86B2E9F2C3FAB"] = $this->3CD86B2E9F2C3FAB->toArray($cycleDetector);
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
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     * 
     * @return void
     */
    public function setCategoryId(int $categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * 
     * @return int|null
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * @param int $zoneId
     * 
     * @return void
     */
    public function setZoneId(int $zoneId = null)
    {
        $this->zoneId = $zoneId;
    }

    /**
     * 
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name = null)
    {
        $this->name = StringUtil::trimToNull($name, 255);
    }

    /**
     * 
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * 
     * @return void
     */
    public function setAmount(float $amount = null)
    {
        $this->amount = $amount;
    }

    /**
     * 
     * @return string|null
     */
    public function getIncludedInPrice()
    {
        return $this->includedInPrice;
    }

    /**
     * @param string $includedInPrice
     * 
     * @return void
     */
    public function setIncludedInPrice(string $includedInPrice = null)
    {
        $this->includedInPrice = StringUtil::trimToNull($includedInPrice, null);
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
     * @param bool $forceReload
     * 
     * @return SyliusTaxCategory|null
     */
    public function get3CD86B2E12469DE2(bool $forceReload = false)
    {
        if ($this->3CD86B2E12469DE2 === null || $forceReload) {
            $this->3CD86B2E12469DE2 = SyliusTaxCategoryService::getInstance()->getEntityByPrimaryKey($this->categoryId);
        }
        return $this->3CD86B2E12469DE2;
    }

    /**
     * @param SyliusTaxCategory $entity
     * 
     * @return void
     */
    public function set3CD86B2E12469DE2(SyliusTaxCategory $entity = null)
    {
        $this->3CD86B2E12469DE2 = $entity;
        $this->categoryId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusZone|null
     */
    public function get3CD86B2E9F2C3FAB(bool $forceReload = false)
    {
        if ($this->3CD86B2E9F2C3FAB === null || $forceReload) {
            $this->3CD86B2E9F2C3FAB = SyliusZoneService::getInstance()->getEntityByPrimaryKey($this->zoneId);
        }
        return $this->3CD86B2E9F2C3FAB;
    }

    /**
     * @param SyliusZone $entity
     * 
     * @return void
     */
    public function set3CD86B2E9F2C3FAB(SyliusZone $entity = null)
    {
        $this->3CD86B2E9F2C3FAB = $entity;
        $this->zoneId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusTaxRate $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusTaxRate $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}