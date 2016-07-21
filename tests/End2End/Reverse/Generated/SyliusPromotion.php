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

class SyliusPromotion implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion";

    const COLUMN_ID = "id";

    const COLUMN_NAME = "name";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_PRIORITY = "priority";

    const COLUMN_EXCLUSIVE = "exclusive";

    const COLUMN_USAGELIMIT = "usage_limit";

    const COLUMN_USED = "used";

    const COLUMN_COUPONBASED = "coupon_based";

    const COLUMN_STARTSAT = "starts_at";

    const COLUMN_ENDSAT = "ends_at";

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
    protected $usageLimit;

    /**
     * @var int
     */
    protected $used;

    /**
     * @var string
     */
    protected $couponBased;

    /**
     * @var SiestaDateTime
     */
    protected $startsAt;

    /**
     * @var SiestaDateTime
     */
    protected $endsAt;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteInt($this->priority) . ',' . Escaper::quoteString($connection, $this->exclusive) . ',' . Escaper::quoteInt($this->usageLimit) . ',' . Escaper::quoteInt($this->used) . ',' . Escaper::quoteString($connection, $this->couponBased) . ',' . Escaper::quoteDateTime($this->startsAt) . ',' . Escaper::quoteDateTime($this->endsAt) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ');';
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
        $this->usageLimit = $resultSet->getIntegerValue("usage_limit");
        $this->used = $resultSet->getIntegerValue("used");
        $this->couponBased = $resultSet->getStringValue("coupon_based");
        $this->startsAt = $resultSet->getDateTime("starts_at");
        $this->endsAt = $resultSet->getDateTime("ends_at");
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
        $this->setUsageLimit($arrayAccessor->getIntegerValue("usageLimit"));
        $this->setUsed($arrayAccessor->getIntegerValue("used"));
        $this->setCouponBased($arrayAccessor->getStringValue("couponBased"));
        $this->setStartsAt($arrayAccessor->getDateTime("startsAt"));
        $this->setEndsAt($arrayAccessor->getDateTime("endsAt"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
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
            "usageLimit" => $this->getUsageLimit(),
            "used" => $this->getUsed(),
            "couponBased" => $this->getCouponBased(),
            "startsAt" => ($this->getStartsAt() !== null) ? $this->getStartsAt()->getJSONDateTime() : null,
            "endsAt" => ($this->getEndsAt() !== null) ? $this->getEndsAt()->getJSONDateTime() : null,
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null
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
    public function getUsageLimit()
    {
        return $this->usageLimit;
    }

    /**
     * @param int $usageLimit
     * 
     * @return void
     */
    public function setUsageLimit(int $usageLimit = null)
    {
        $this->usageLimit = $usageLimit;
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
    public function getCouponBased()
    {
        return $this->couponBased;
    }

    /**
     * @param string $couponBased
     * 
     * @return void
     */
    public function setCouponBased(string $couponBased = null)
    {
        $this->couponBased = StringUtil::trimToNull($couponBased, null);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * @param SiestaDateTime $startsAt
     * 
     * @return void
     */
    public function setStartsAt(SiestaDateTime $startsAt = null)
    {
        $this->startsAt = $startsAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @param SiestaDateTime $endsAt
     * 
     * @return void
     */
    public function setEndsAt(SiestaDateTime $endsAt = null)
    {
        $this->endsAt = $endsAt;
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
     * @param SyliusPromotion $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPromotion $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}