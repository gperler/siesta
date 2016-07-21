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

class sylius_promotion implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion";

    const COLUMN_ID = "id";

    const COLUMN_NAME = "name";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_PRIORITY = "priority";

    const COLUMN_EXCLUSIVE = "exclusive";

    const COLUMN_USAGE_LIMIT = "usage_limit";

    const COLUMN_USED = "used";

    const COLUMN_COUPON_BASED = "coupon_based";

    const COLUMN_STARTS_AT = "starts_at";

    const COLUMN_ENDS_AT = "ends_at";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

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
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var string
     */
    protected $exclusive;

    /**
     * @var int
     */
    protected $usage_limit;

    /**
     * @var int
     */
    protected $used;

    /**
     * @var string
     */
    protected $coupon_based;

    /**
     * @var SiestaDateTime
     */
    protected $starts_at;

    /**
     * @var SiestaDateTime
     */
    protected $ends_at;

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
        $spCall = ($this->_existing) ? "CALL sylius_promotion_U(" : "CALL sylius_promotion_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteInt($this->priority) . ',' . Escaper::quoteString($connection, $this->exclusive) . ',' . Escaper::quoteInt($this->usage_limit) . ',' . Escaper::quoteInt($this->used) . ',' . Escaper::quoteString($connection, $this->coupon_based) . ',' . Escaper::quoteDateTime($this->starts_at) . ',' . Escaper::quoteDateTime($this->ends_at) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ');';
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
        $this->name = $resultSet->getStringValue("name");
        $this->description = $resultSet->getStringValue("description");
        $this->priority = $resultSet->getIntegerValue("priority");
        $this->exclusive = $resultSet->getStringValue("exclusive");
        $this->usage_limit = $resultSet->getIntegerValue("usage_limit");
        $this->used = $resultSet->getIntegerValue("used");
        $this->coupon_based = $resultSet->getStringValue("coupon_based");
        $this->starts_at = $resultSet->getDateTime("starts_at");
        $this->ends_at = $resultSet->getDateTime("ends_at");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
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
        $connection->execute("CALL sylius_promotion_DB_PK($id)");
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
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setPriority($arrayAccessor->getIntegerValue("priority"));
        $this->setExclusive($arrayAccessor->getStringValue("exclusive"));
        $this->setUsage_limit($arrayAccessor->getIntegerValue("usage_limit"));
        $this->setUsed($arrayAccessor->getIntegerValue("used"));
        $this->setCoupon_based($arrayAccessor->getStringValue("coupon_based"));
        $this->setStarts_at($arrayAccessor->getDateTime("starts_at"));
        $this->setEnds_at($arrayAccessor->getDateTime("ends_at"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->_existing = ($this->id !== null);
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
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "priority" => $this->getPriority(),
            "exclusive" => $this->getExclusive(),
            "usage_limit" => $this->getUsage_limit(),
            "used" => $this->getUsed(),
            "coupon_based" => $this->getCoupon_based(),
            "starts_at" => ($this->getStarts_at() !== null) ? $this->getStarts_at()->getJSONDateTime() : null,
            "ends_at" => ($this->getEnds_at() !== null) ? $this->getEnds_at()->getJSONDateTime() : null,
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null
        ];
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
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return void
     */
    public function setDescription(string $description = null)
    {
        $this->description = StringUtil::trimToNull($description, 255);
    }

    /**
     * 
     * @return int|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * 
     * @return void
     */
    public function setPriority(int $priority = null)
    {
        $this->priority = $priority;
    }

    /**
     * 
     * @return string|null
     */
    public function getExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param string $exclusive
     * 
     * @return void
     */
    public function setExclusive(string $exclusive = null)
    {
        $this->exclusive = StringUtil::trimToNull($exclusive, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getUsage_limit()
    {
        return $this->usage_limit;
    }

    /**
     * @param int $usage_limit
     * 
     * @return void
     */
    public function setUsage_limit(int $usage_limit = null)
    {
        $this->usage_limit = $usage_limit;
    }

    /**
     * 
     * @return int|null
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @param int $used
     * 
     * @return void
     */
    public function setUsed(int $used = null)
    {
        $this->used = $used;
    }

    /**
     * 
     * @return string|null
     */
    public function getCoupon_based()
    {
        return $this->coupon_based;
    }

    /**
     * @param string $coupon_based
     * 
     * @return void
     */
    public function setCoupon_based(string $coupon_based = null)
    {
        $this->coupon_based = StringUtil::trimToNull($coupon_based, null);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getStarts_at()
    {
        return $this->starts_at;
    }

    /**
     * @param SiestaDateTime $starts_at
     * 
     * @return void
     */
    public function setStarts_at(SiestaDateTime $starts_at = null)
    {
        $this->starts_at = $starts_at;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getEnds_at()
    {
        return $this->ends_at;
    }

    /**
     * @param SiestaDateTime $ends_at
     * 
     * @return void
     */
    public function setEnds_at(SiestaDateTime $ends_at = null)
    {
        $this->ends_at = $ends_at;
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
     * @param sylius_promotion $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_promotion $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}