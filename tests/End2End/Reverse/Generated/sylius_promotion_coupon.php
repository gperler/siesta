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

class sylius_promotion_coupon implements ArraySerializable
{

    const TABLE_NAME = "sylius_promotion_coupon";

    const COLUMN_ID = "id";

    const COLUMN_PROMOTION_ID = "promotion_id";

    const COLUMN_CODE = "code";

    const COLUMN_USAGE_LIMIT = "usage_limit";

    const COLUMN_USED = "used";

    const COLUMN_EXPIRES_AT = "expires_at";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

    const COLUMN_PER_CUSTOMER_USAGE_LIMIT = "per_customer_usage_limit";

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
    protected $promotion_id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $usage_limit;

    /**
     * @var int
     */
    protected $used;

    /**
     * @var SiestaDateTime
     */
    protected $expires_at;

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
     * @var int
     */
    protected $per_customer_usage_limit;

    /**
     * @var sylius_promotion
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->promotion_id) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteInt($this->usage_limit) . ',' . Escaper::quoteInt($this->used) . ',' . Escaper::quoteDateTime($this->expires_at) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ',' . Escaper::quoteInt($this->per_customer_usage_limit) . ');';
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
        $this->promotion_id = $resultSet->getIntegerValue("promotion_id");
        $this->code = $resultSet->getStringValue("code");
        $this->usage_limit = $resultSet->getIntegerValue("usage_limit");
        $this->used = $resultSet->getIntegerValue("used");
        $this->expires_at = $resultSet->getDateTime("expires_at");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
        $this->per_customer_usage_limit = $resultSet->getIntegerValue("per_customer_usage_limit");
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
        $this->setPromotion_id($arrayAccessor->getIntegerValue("promotion_id"));
        $this->setCode($arrayAccessor->getStringValue("code"));
        $this->setUsage_limit($arrayAccessor->getIntegerValue("usage_limit"));
        $this->setUsed($arrayAccessor->getIntegerValue("used"));
        $this->setExpires_at($arrayAccessor->getDateTime("expires_at"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->setPer_customer_usage_limit($arrayAccessor->getIntegerValue("per_customer_usage_limit"));
        $this->_existing = ($this->id !== null);
        $B04EBA85139DF194Array = $arrayAccessor->getArray("B04EBA85139DF194");
        if ($B04EBA85139DF194Array !== null) {
            $B04EBA85139DF194 = sylius_promotionService::getInstance()->newInstance();
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
            "promotion_id" => $this->getPromotion_id(),
            "code" => $this->getCode(),
            "usage_limit" => $this->getUsage_limit(),
            "used" => $this->getUsed(),
            "expires_at" => ($this->getExpires_at() !== null) ? $this->getExpires_at()->getJSONDateTime() : null,
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null,
            "per_customer_usage_limit" => $this->getPer_customer_usage_limit()
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
    public function getPromotion_id()
    {
        return $this->promotion_id;
    }

    /**
     * @param int $promotion_id
     * 
     * @return void
     */
    public function setPromotion_id(int $promotion_id = null)
    {
        $this->promotion_id = $promotion_id;
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
     * @return SiestaDateTime|null
     */
    public function getExpires_at()
    {
        return $this->expires_at;
    }

    /**
     * @param SiestaDateTime $expires_at
     * 
     * @return void
     */
    public function setExpires_at(SiestaDateTime $expires_at = null)
    {
        $this->expires_at = $expires_at;
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
     * @return int|null
     */
    public function getPer_customer_usage_limit()
    {
        return $this->per_customer_usage_limit;
    }

    /**
     * @param int $per_customer_usage_limit
     * 
     * @return void
     */
    public function setPer_customer_usage_limit(int $per_customer_usage_limit = null)
    {
        $this->per_customer_usage_limit = $per_customer_usage_limit;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_promotion|null
     */
    public function getB04EBA85139DF194(bool $forceReload = false)
    {
        if ($this->B04EBA85139DF194 === null || $forceReload) {
            $this->B04EBA85139DF194 = sylius_promotionService::getInstance()->getEntityByPrimaryKey($this->promotion_id);
        }
        return $this->B04EBA85139DF194;
    }

    /**
     * @param sylius_promotion $entity
     * 
     * @return void
     */
    public function setB04EBA85139DF194(sylius_promotion $entity = null)
    {
        $this->B04EBA85139DF194 = $entity;
        $this->promotion_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_promotion_coupon $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_promotion_coupon $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}