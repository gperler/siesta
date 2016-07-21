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

class sylius_payment_method implements ArraySerializable
{

    const TABLE_NAME = "sylius_payment_method";

    const COLUMN_ID = "id";

    const COLUMN_GATEWAY = "gateway";

    const COLUMN_ENVIRONMENT = "environment";

    const COLUMN_IS_ENABLED = "is_enabled";

    const COLUMN_FEE_CALCULATOR = "fee_calculator";

    const COLUMN_FEE_CALCULATOR_CONFIGURATION = "fee_calculator_configuration";

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
    protected $gateway;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $is_enabled;

    /**
     * @var string
     */
    protected $fee_calculator;

    /**
     * @var string
     */
    protected $fee_calculator_configuration;

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
        $spCall = ($this->_existing) ? "CALL sylius_payment_method_U(" : "CALL sylius_payment_method_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->gateway) . ',' . Escaper::quoteString($connection, $this->environment) . ',' . Escaper::quoteString($connection, $this->is_enabled) . ',' . Escaper::quoteString($connection, $this->fee_calculator) . ',' . Escaper::quoteString($connection, $this->fee_calculator_configuration) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        $this->gateway = $resultSet->getStringValue("gateway");
        $this->environment = $resultSet->getStringValue("environment");
        $this->is_enabled = $resultSet->getStringValue("is_enabled");
        $this->fee_calculator = $resultSet->getStringValue("fee_calculator");
        $this->fee_calculator_configuration = $resultSet->getStringValue("fee_calculator_configuration");
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
        $connection->execute("CALL sylius_payment_method_DB_PK($id)");
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
        $this->setGateway($arrayAccessor->getStringValue("gateway"));
        $this->setEnvironment($arrayAccessor->getStringValue("environment"));
        $this->setIs_enabled($arrayAccessor->getStringValue("is_enabled"));
        $this->setFee_calculator($arrayAccessor->getStringValue("fee_calculator"));
        $this->setFee_calculator_configuration($arrayAccessor->getStringValue("fee_calculator_configuration"));
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
            "gateway" => $this->getGateway(),
            "environment" => $this->getEnvironment(),
            "is_enabled" => $this->getIs_enabled(),
            "fee_calculator" => $this->getFee_calculator(),
            "fee_calculator_configuration" => $this->getFee_calculator_configuration(),
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
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param string $gateway
     * 
     * @return void
     */
    public function setGateway(string $gateway = null)
    {
        $this->gateway = StringUtil::trimToNull($gateway, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     * 
     * @return void
     */
    public function setEnvironment(string $environment = null)
    {
        $this->environment = StringUtil::trimToNull($environment, 255);
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
     * @return string|null
     */
    public function getFee_calculator()
    {
        return $this->fee_calculator;
    }

    /**
     * @param string $fee_calculator
     * 
     * @return void
     */
    public function setFee_calculator(string $fee_calculator = null)
    {
        $this->fee_calculator = StringUtil::trimToNull($fee_calculator, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getFee_calculator_configuration()
    {
        return $this->fee_calculator_configuration;
    }

    /**
     * @param string $fee_calculator_configuration
     * 
     * @return void
     */
    public function setFee_calculator_configuration(string $fee_calculator_configuration = null)
    {
        $this->fee_calculator_configuration = StringUtil::trimToNull($fee_calculator_configuration, null);
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
     * @param sylius_payment_method $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_payment_method $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}