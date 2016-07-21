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

class sylius_credit_card implements ArraySerializable
{

    const TABLE_NAME = "sylius_credit_card";

    const COLUMN_ID = "id";

    const COLUMN_TOKEN = "token";

    const COLUMN_TYPE = "type";

    const COLUMN_CARDHOLDER_NAME = "cardholder_name";

    const COLUMN_NUMBER = "number";

    const COLUMN_SECURITY_CODE = "security_code";

    const COLUMN_EXPIRY_MONTH = "expiry_month";

    const COLUMN_EXPIRY_YEAR = "expiry_year";

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
    protected $cardholder_name;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $security_code;

    /**
     * @var int
     */
    protected $expiry_month;

    /**
     * @var int
     */
    protected $expiry_year;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->token) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->cardholder_name) . ',' . Escaper::quoteString($connection, $this->number) . ',' . Escaper::quoteString($connection, $this->security_code) . ',' . Escaper::quoteInt($this->expiry_month) . ',' . Escaper::quoteInt($this->expiry_year) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        $this->cardholder_name = $resultSet->getStringValue("cardholder_name");
        $this->number = $resultSet->getStringValue("number");
        $this->security_code = $resultSet->getStringValue("security_code");
        $this->expiry_month = $resultSet->getIntegerValue("expiry_month");
        $this->expiry_year = $resultSet->getIntegerValue("expiry_year");
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
        $this->setCardholder_name($arrayAccessor->getStringValue("cardholder_name"));
        $this->setNumber($arrayAccessor->getStringValue("number"));
        $this->setSecurity_code($arrayAccessor->getStringValue("security_code"));
        $this->setExpiry_month($arrayAccessor->getIntegerValue("expiry_month"));
        $this->setExpiry_year($arrayAccessor->getIntegerValue("expiry_year"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
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
            "cardholder_name" => $this->getCardholder_name(),
            "number" => $this->getNumber(),
            "security_code" => $this->getSecurity_code(),
            "expiry_month" => $this->getExpiry_month(),
            "expiry_year" => $this->getExpiry_year(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
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
    public function getCardholder_name()
    {
        return $this->cardholder_name;
    }

    /**
     * @param string $cardholder_name
     * 
     * @return void
     */
    public function setCardholder_name(string $cardholder_name = null)
    {
        $this->cardholder_name = StringUtil::trimToNull($cardholder_name, 255);
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
    public function getSecurity_code()
    {
        return $this->security_code;
    }

    /**
     * @param string $security_code
     * 
     * @return void
     */
    public function setSecurity_code(string $security_code = null)
    {
        $this->security_code = StringUtil::trimToNull($security_code, 255);
    }

    /**
     * 
     * @return int|null
     */
    public function getExpiry_month()
    {
        return $this->expiry_month;
    }

    /**
     * @param int $expiry_month
     * 
     * @return void
     */
    public function setExpiry_month(int $expiry_month = null)
    {
        $this->expiry_month = $expiry_month;
    }

    /**
     * 
     * @return int|null
     */
    public function getExpiry_year()
    {
        return $this->expiry_year;
    }

    /**
     * @param int $expiry_year
     * 
     * @return void
     */
    public function setExpiry_year(int $expiry_year = null)
    {
        $this->expiry_year = $expiry_year;
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
     * @param sylius_credit_card $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_credit_card $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}