<?php

namespace siestaphp\driver;

use siestaphp\driver\exceptions\ConnectException;
use siestaphp\driver\mysqli\MySQLDriver;
use siestaphp\exceptions\InvalidConfiguration;
use siestaphp\util\Util;

/**
 * Class ConnectionFactory
 * @package siestaphp\driver
 */
class ConnectionFactory
{

    const EXCEPTION_NO_DEFAULT = "No default connection available";

    const EXCEPTION_NOT_CONFIGURED = "Connection with name %s is not configured";

    const EXCEPTION_DRIVER_NOT_IMPLEMENTD = "Driver for %s is not implemented";

    /**
     * @var ConnectionFactory
     */
    private static $connectionFactory;

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws InvalidConfiguration
     * @return Connection
     */
    public static function addConnection(ConnectionData $connectionData)
    {
        return self::getInstance()->_addConnection($connectionData);
    }

    /**
     * @param null $name
     *
     * @return Connection
     * @throws InvalidConfiguration
     */
    public static function getConnection($name = null)
    {
        return self::getInstance()->_getConnection($name);
    }

    /**
     * @return ConnectionFactory
     */
    public static function getInstance()
    {
        if (!self::$connectionFactory) {
            self::$connectionFactory = new static();
        }
        return self::$connectionFactory;
    }

    /**
     * @var Driver[]
     */
    protected $driverList;

    /**
     * @var Connection[]
     */
    protected $connectionList;

    /**
     * @var Connection
     */
    protected $defaultConnection;

    /**
     *
     */
    public function __construct()
    {
        $this->driverList = array();
        $this->connectionList = array();
    }

    /**
     * @param $name
     *
     * @return Connection
     * @throws InvalidConfiguration
     */
    protected function _getConnection($name = null)
    {
        if ($name === null) {
            return $this->getDefaultConnection();
        }

        $connection = Util::getFromArray($this->connectionList, $name);
        if ($connection === null) {
            throw new InvalidConfiguration(sprintf(self::EXCEPTION_NOT_CONFIGURED, $name));
        }

        return $connection;
    }

    /**
     * @return Connection
     * @throws InvalidConfiguration
     */
    protected function getDefaultConnection()
    {
        if ($this->defaultConnection === null) {
            throw new InvalidConfiguration(self::EXCEPTION_NO_DEFAULT);
        }
        return $this->defaultConnection;
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws InvalidConfiguration
     * @return Connection
     */
    protected function _addConnection(ConnectionData $connectionData)
    {
        $connection = Util::getFromArray($this->connectionList, $connectionData->name);
        if ($connection !== null) {
            return $connection;
        }
        $driver = $this->_getDriver($connectionData);
        $connection = $driver->connect($connectionData);

        $this->connectionList[$connectionData->name] = $connection;
        if ($this->defaultConnection === null or $connectionData->isDefault) {
            $this->defaultConnection = $connection;
        }

        return $connection;
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Driver
     * @throws InvalidConfiguration
     */
    protected function _getDriver(ConnectionData $connectionData)
    {
        $driver = Util::getFromArray($this->driverList, $connectionData->database);
        if ($driver === null) {
            $this->driverList[$connectionData->database] = $this->instantiateDriver($connectionData);
        }
        return $this->driverList[$connectionData->database];
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Driver
     * @throws InvalidConfiguration
     */
    protected function instantiateDriver(ConnectionData $connectionData)
    {
        switch ($connectionData->driver) {
            case "mysql":
                return new MySQLDriver();
            default:
                throw new InvalidConfiguration(sprintf(self::EXCEPTION_DRIVER_NOT_IMPLEMENTD, $connectionData->driver));
        }
    }

}