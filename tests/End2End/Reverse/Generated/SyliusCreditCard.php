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

class SyliusCreditCard implements ArraySerializable
{

    const TABLE_NAME = "sylius_credit_card";

    const COLUMN_ID = "id";

    const COLUMN_TOKEN = "token";

    const COLUMN_TYPE = "type";

    const COLUMN_CARDHOLDERNAME = "cardholder_name";

    const COLUMN_NUMBER = "number";

    const COLUMN_SECURITYCODE = "security_code";

    const COLUMN_EXPIRYMONTH = "expiry_month";

    const COLUMN_EXPIRYYEAR = "expiry_year";

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
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $cardholderName;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $securityCode;

    /**
     * @var int
     */
    protected $expiryMonth;

    /**
     * @var int
     */
    protected $expiryYear;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

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
        $spCall = ($this->_existing) ? "CALL sylius_credit_card_U(" : "CALL sylius_credit_card_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->token) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->cardholderName) . ',' . Escaper::quoteString($connection, $this->number) . ',' . Escaper::quoteString($connection, $this->securityCode) . ',' . Escaper::quoteInt($this->expiryMonth) . ',' . Escaper::quoteInt($this->expiryYear) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        $this->token = $resultSet->getStringValue("token");
        $this->type = $resultSet->getStringValue("type");
        $this->cardholderName = $resultSet->getStringValue("cardholder_name");
        $this->number = $resultSet->getStringValue("number");
        $this->securityCode = $resultSet->getStringValue("security_code");
        $this->expiryMonth = $resultSet->getIntegerValue("expiry_month");
        $this->expiryYear = $resultSet->getIntegerValue("expiry_year");
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
        $connection->execute("CALL sylius_credit_card_DB_PK($id)");
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
        $this->setToken($arrayAccessor->getStringValue("token"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setCardholderName($arrayAccessor->getStringValue("cardholderName"));
        $this->setNumber($arrayAccessor->getStringValue("number"));
        $this->setSecurityCode($arrayAccessor->getStringValue("securityCode"));
        $this->setExpiryMonth($arrayAccessor->getIntegerValue("expiryMonth"));
        $this->setExpiryYear($arrayAccessor->getIntegerValue("expiryYear"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
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
            "token" => $this->getToken(),
            "type" => $this->getType(),
            "cardholderName" => $this->getCardholderName(),
            "number" => $this->getNumber(),
            "securityCode" => $this->getSecurityCode(),
            "expiryMonth" => $this->getExpiryMonth(),
            "expiryYear" => $this->getExpiryYear(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * 
     * @return void
     */
    public function setToken(string $token = null)
    {
        $this->token = StringUtil::trimToNull($token, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type = null)
    {
        $this->type = StringUtil::trimToNull($type, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getCardholderName()
    {
        return $this->cardholderName;
    }

    /**
     * @param string $cardholderName
     * 
     * @return void
     */
    public function setCardholderName(string $cardholderName = null)
    {
        $this->cardholderName = StringUtil::trimToNull($cardholderName, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * 
     * @return void
     */
    public function setNumber(string $number = null)
    {
        $this->number = StringUtil::trimToNull($number, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * @param string $securityCode
     * 
     * @return void
     */
    public function setSecurityCode(string $securityCode = null)
    {
        $this->securityCode = StringUtil::trimToNull($securityCode, 255);
    }

    /**
     * 
     * @return int|null
     */
    public function getExpiryMonth()
    {
        return $this->expiryMonth;
    }

    /**
     * @param int $expiryMonth
     * 
     * @return void
     */
    public function setExpiryMonth(int $expiryMonth = null)
    {
        $this->expiryMonth = $expiryMonth;
    }

    /**
     * 
     * @return int|null
     */
    public function getExpiryYear()
    {
        return $this->expiryYear;
    }

    /**
     * @param int $expiryYear
     * 
     * @return void
     */
    public function setExpiryYear(int $expiryYear = null)
    {
        $this->expiryYear = $expiryYear;
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
     * @param SyliusCreditCard $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusCreditCard $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}