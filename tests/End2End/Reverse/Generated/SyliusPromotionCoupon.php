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

class SyliusPromotionCoupon implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_coupon";

    const COLUMN_ID = "id";

    const COLUMN_PROMOTIONID = "promotion_id";

    const COLUMN_CODE = "code";

    const COLUMN_USAGELIMIT = "usage_limit";

    const COLUMN_USED = "used";

    const COLUMN_EXPIRESAT = "expires_at";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

    const COLUMN_PERCUSTOMERUSAGELIMIT = "per_customer_usage_limit";

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
    protected $promotionId;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $usageLimit;

    /**
     * @var int
     */
    protected $used;

    /**
     * @var SiestaDateTime
     */
    protected $expiresAt;

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
     * @var int
     */
    protected $perCustomerUsageLimit;

    /**
     * @var SyliusPromotion
     */
    protected $B04EBA85139DF194;

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
        $spCall = ($this->_existing) ? "CALL sylius_promotion_coupon_U(" : "CALL sylius_promotion_coupon_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->promotionId) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteInt($this->usageLimit) . ',' . Escaper::quoteInt($this->used) . ',' . Escaper::quoteDateTime($this->expiresAt) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ',' . Escaper::quoteInt($this->perCustomerUsageLimit) . ');';
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
        if ($cascade && $this->B04EBA85139DF194 !== null) {
            $this->B04EBA85139DF194->save($cascade, $cycleDetector, $connectionName);
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
        $this->promotionId = $resultSet->getIntegerValue("promotion_id");
        $this->code = $resultSet->getStringValue("code");
        $this->usageLimit = $resultSet->getIntegerValue("usage_limit");
        $this->used = $resultSet->getIntegerValue("used");
        $this->expiresAt = $resultSet->getDateTime("expires_at");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
        $this->perCustomerUsageLimit = $resultSet->getIntegerValue("per_customer_usage_limit");
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
        $connection->execute("CALL sylius_promotion_coupon_DB_PK($id)");
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
        $this->setPromotionId($arrayAccessor->getIntegerValue("promotionId"));
        $this->setCode($arrayAccessor->getStringValue("code"));
        $this->setUsageLimit($arrayAccessor->getIntegerValue("usageLimit"));
        $this->setUsed($arrayAccessor->getIntegerValue("used"));
        $this->setExpiresAt($arrayAccessor->getDateTime("expiresAt"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->setPerCustomerUsageLimit($arrayAccessor->getIntegerValue("perCustomerUsageLimit"));
        $this->_existing = ($this->id !== null);
        $B04EBA85139DF194Array = $arrayAccessor->getArray("B04EBA85139DF194");
        if ($B04EBA85139DF194Array !== null) {
            $B04EBA85139DF194 = SyliusPromotionService::getInstance()->newInstance();
            $B04EBA85139DF194->fromArray($B04EBA85139DF194Array);
            $this->setB04EBA85139DF194($B04EBA85139DF194);
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
            "promotionId" => $this->getPromotionId(),
            "code" => $this->getCode(),
            "usageLimit" => $this->getUsageLimit(),
            "used" => $this->getUsed(),
            "expiresAt" => ($this->getExpiresAt() !== null) ? $this->getExpiresAt()->getJSONDateTime() : null,
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null,
            "perCustomerUsageLimit" => $this->getPerCustomerUsageLimit()
        ];
        if ($this->B04EBA85139DF194 !== null) {
            $result["B04EBA85139DF194"] = $this->B04EBA85139DF194->toArray($cycleDetector);
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
    public function getPromotionId()
    {
        return $this->promotionId;
    }

    /**
     * @param int $promotionId
     * 
     * @return void
     */
    public function setPromotionId(int $promotionId = null)
    {
        $this->promotionId = $promotionId;
    }

    /**
     * 
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * 
     * @return void
     */
    public function setCode(string $code = null)
    {
        $this->code = StringUtil::trimToNull($code, 255);
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
     * @return SiestaDateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param SiestaDateTime $expiresAt
     * 
     * @return void
     */
    public function setExpiresAt(SiestaDateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
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
     * @return int|null
     */
    public function getPerCustomerUsageLimit()
    {
        return $this->perCustomerUsageLimit;
    }

    /**
     * @param int $perCustomerUsageLimit
     * 
     * @return void
     */
    public function setPerCustomerUsageLimit(int $perCustomerUsageLimit = null)
    {
        $this->perCustomerUsageLimit = $perCustomerUsageLimit;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusPromotion|null
     */
    public function getB04EBA85139DF194(bool $forceReload = false)
    {
        if ($this->B04EBA85139DF194 === null || $forceReload) {
            $this->B04EBA85139DF194 = SyliusPromotionService::getInstance()->getEntityByPrimaryKey($this->promotionId);
        }
        return $this->B04EBA85139DF194;
    }

    /**
     * @param SyliusPromotion $entity
     * 
     * @return void
     */
    public function setB04EBA85139DF194(SyliusPromotion $entity = null)
    {
        $this->B04EBA85139DF194 = $entity;
        $this->promotionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusPromotionCoupon $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPromotionCoupon $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}