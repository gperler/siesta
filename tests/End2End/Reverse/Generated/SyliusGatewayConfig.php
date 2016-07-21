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
use Siesta\Util\StringUtil;

class SyliusGatewayConfig implements ArraySerializable
{

    const TABLE_NAME = "sylius_gateway_config";

    const COLUMN_ID = "id";

    const COLUMN_CONFIG = "config";

    const COLUMN_GATEWAYNAME = "gateway_name";

    const COLUMN_FACTORYNAME = "factory_name";

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
    protected $config;

    /**
     * @var string
     */
    protected $gatewayName;

    /**
     * @var string
     */
    protected $factoryName;

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
        $spCall = ($this->_existing) ? "CALL sylius_gateway_config_U(" : "CALL sylius_gateway_config_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->config) . ',' . Escaper::quoteString($connection, $this->gatewayName) . ',' . Escaper::quoteString($connection, $this->factoryName) . ');';
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
        $this->config = $resultSet->getStringValue("config");
        $this->gatewayName = $resultSet->getStringValue("gateway_name");
        $this->factoryName = $resultSet->getStringValue("factory_name");
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
        $connection->execute("CALL sylius_gateway_config_DB_PK($id)");
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
        $this->setConfig($arrayAccessor->getStringValue("config"));
        $this->setGatewayName($arrayAccessor->getStringValue("gatewayName"));
        $this->setFactoryName($arrayAccessor->getStringValue("factoryName"));
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
            "config" => $this->getConfig(),
            "gatewayName" => $this->getGatewayName(),
            "factoryName" => $this->getFactoryName()
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
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $config
     * 
     * @return void
     */
    public function setConfig(string $config = null)
    {
        $this->config = StringUtil::trimToNull($config, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getGatewayName()
    {
        return $this->gatewayName;
    }

    /**
     * @param string $gatewayName
     * 
     * @return void
     */
    public function setGatewayName(string $gatewayName = null)
    {
        $this->gatewayName = StringUtil::trimToNull($gatewayName, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getFactoryName()
    {
        return $this->factoryName;
    }

    /**
     * @param string $factoryName
     * 
     * @return void
     */
    public function setFactoryName(string $factoryName = null)
    {
        $this->factoryName = StringUtil::trimToNull($factoryName, 255);
    }

    /**
     * @param SyliusGatewayConfig $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusGatewayConfig $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}