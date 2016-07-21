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

class SyliusPaymentMethod implements ArraySerializable
{

    const TABLE_NAME = "sylius_payment_method";

    const COLUMN_ID = "id";

    const COLUMN_GATEWAY = "gateway";

    const COLUMN_ENVIRONMENT = "environment";

    const COLUMN_ISENABLED = "is_enabled";

    const COLUMN_FEECALCULATOR = "fee_calculator";

    const COLUMN_FEECALCULATORCONFIGURATION = "fee_calculator_configuration";

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
    protected $gateway;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $isEnabled;

    /**
     * @var string
     */
    protected $feeCalculator;

    /**
     * @var string
     */
    protected $feeCalculatorConfiguration;

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
        $spCall = ($this->_existing) ? "CALL sylius_payment_method_U(" : "CALL sylius_payment_method_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->gateway) . ',' . Escaper::quoteString($connection, $this->environment) . ',' . Escaper::quoteString($connection, $this->isEnabled) . ',' . Escaper::quoteString($connection, $this->feeCalculator) . ',' . Escaper::quoteString($connection, $this->feeCalculatorConfiguration) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        $this->isEnabled = $resultSet->getStringValue("is_enabled");
        $this->feeCalculator = $resultSet->getStringValue("fee_calculator");
        $this->feeCalculatorConfiguration = $resultSet->getStringValue("fee_calculator_configuration");
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
        $this->setIsEnabled($arrayAccessor->getStringValue("isEnabled"));
        $this->setFeeCalculator($arrayAccessor->getStringValue("feeCalculator"));
        $this->setFeeCalculatorConfiguration($arrayAccessor->getStringValue("feeCalculatorConfiguration"));
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
            "gateway" => $this->getGateway(),
            "environment" => $this->getEnvironment(),
            "isEnabled" => $this->getIsEnabled(),
            "feeCalculator" => $this->getFeeCalculator(),
            "feeCalculatorConfiguration" => $this->getFeeCalculatorConfiguration(),
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
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param string $isEnabled
     * 
     * @return void
     */
    public function setIsEnabled(string $isEnabled = null)
    {
        $this->isEnabled = StringUtil::trimToNull($isEnabled, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getFeeCalculator()
    {
        return $this->feeCalculator;
    }

    /**
     * @param string $feeCalculator
     * 
     * @return void
     */
    public function setFeeCalculator(string $feeCalculator = null)
    {
        $this->feeCalculator = StringUtil::trimToNull($feeCalculator, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getFeeCalculatorConfiguration()
    {
        return $this->feeCalculatorConfiguration;
    }

    /**
     * @param string $feeCalculatorConfiguration
     * 
     * @return void
     */
    public function setFeeCalculatorConfiguration(string $feeCalculatorConfiguration = null)
    {
        $this->feeCalculatorConfiguration = StringUtil::trimToNull($feeCalculatorConfiguration, null);
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
     * @param SyliusPaymentMethod $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPaymentMethod $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}