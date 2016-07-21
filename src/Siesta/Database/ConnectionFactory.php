<?php

declare(strict_types = 1);

namespace Siesta\Database;

use Siesta\Database\Exception\ConnectException;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
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
     * @throws InvalidConfigurationException
     * @return Connection
     */
    public static function addConnection(ConnectionData $connectionData) : Connection
    {
        return self::getInstance()->_addConnection($connectionData);
    }

    /**
     * @param null $name
     *
     * @return Connection
     * @throws InvalidConfigurationException
     */
    public static function getConnection($name = null) : Connection
    {
        return self::getInstance()->_getConnection($name);
    }

    /**
     * @return ConnectionFactory
     */
    public static function getInstance() : ConnectionFactory
    {
        if (!self::$connectionFactory) {

            self::$connectionFactory = new static();
        }
        return self::$connectionFactory;
    }

    /**
     *
     */
    public static function reset()
    {
        self::$connectionFactory = null;
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
    private function __construct()
    {
        $this->driverList = [];
        $this->connectionList = [];
    }

    /**
     * @param $name
     *
     * @return Connection
     * @throws InvalidConfigurationException
     */
    protected function _getConnection($name = null)
    {
        if ($name === null) {
            return $this->getDefaultConnection();
        }

        $connection = ArrayUtil::getFromArray($this->connectionList, $name);
        if ($connection === null) {
            throw new InvalidConfigurationException(sprintf(self::EXCEPTION_NOT_CONFIGURED, $name));
        }

        return $connection;
    }

    /**
     * @return Connection
     * @throws InvalidConfigurationException
     */
    protected function getDefaultConnection() : Connection
    {
        if ($this->defaultConnection === null) {
            throw new InvalidConfigurationException(self::EXCEPTION_NO_DEFAULT);
        }
        return $this->defaultConnection;
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws InvalidConfigurationException
     * @return Connection
     */
    protected function _addConnection(ConnectionData $connectionData) : Connection
    {
        $connection = ArrayUtil::getFromArray($this->connectionList, $connectionData->name);
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
     * @throws InvalidConfigurationException
     */
    protected function _getDriver(ConnectionData $connectionData) : Driver
    {
        $driver = ArrayUtil::getFromArray($this->driverList, $connectionData->database);

        if ($driver === null) {
            $this->driverList[$connectionData->database] = $this->instantiateDriver($connectionData);
        }
        return $this->driverList[$connectionData->database];
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Driver
     * @throws InvalidConfigurationException
     */
    protected function instantiateDriver(ConnectionData $connectionData) : Driver
    {
        $class = $connectionData->driver;

        if (!class_exists($class)) {
            throw new InvalidConfigurationException(sprintf(self::EXCEPTION_DRIVER_NOT_IMPLEMENTD, $connectionData->driver));
        }
        return new $class;

    }

}

