<?php

namespace siestaphp\driver;

use Codeception\Util\Debug;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\driver\exceptions\DatabaseConfigurationException;
use siestaphp\driver\mysqli\MySQLDriver;
use siestaphp\util\Util;

/**
 * Class ConnectionFactory
 * @package siestaphp\driver
 */
class ConnectionFactory
{

    /**
     * @var ConnectionFactory
     */
    private static $connectionFactory;

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws DatabaseConfigurationException
     * @return Connection
     */
    public static function addConnection(ConnectionData $connectionData)
    {
        return self::getInstance()->_getConnection($connectionData);
    }

    /**
     * @param null $name
     *
     * @return Connection
     * @throws DatabaseConfigurationException
     */
    public static function getConnection($name = null)
    {
        return self::getInstance()->_getConnectionByName($name);
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
     * @throws DatabaseConfigurationException
     */
    private function _getConnectionByName($name = null)
    {
        $key = ($name) ? $name : $this->getFirstConnectionKey();
        Debug::debug($key);
        $connection = Util::getFromArray($this->connectionList, $key);
        if (!$connection) {
            throw new DatabaseConfigurationException(null, "Connection with name " . $name . " is not configured");
        }
        return $connection;
    }

    /**
     * @return string
     */
    private function getFirstConnectionKey()
    {
        reset($this->connectionList);
        return key($this->connectionList);
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws DatabaseConfigurationException
     * @return Connection
     */
    private function _getConnection(ConnectionData $connectionData)
    {
        $connection = Util::getFromArray($this->connectionList, $connectionData->name);
        if (!$connection) {
            $driver = $this->_getDriver($connectionData);
            $this->connectionList[$connectionData->name] = $driver->connect($connectionData);
        }
        return $this->connectionList[$connectionData->name];
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Driver
     * @throws DatabaseConfigurationException
     */
    private function _getDriver(ConnectionData $connectionData)
    {
        $driver = Util::getFromArray($this->driverList, $connectionData->database);
        if (!$driver) {
            $this->driverList[$connectionData->database] = $this->instantiateDriver($connectionData);
        }
        return $this->driverList[$connectionData->database];
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Driver
     * @throws DatabaseConfigurationException
     */
    private function instantiateDriver(ConnectionData $connectionData)
    {
        switch ($connectionData->driver) {
            case "mysql":
                return new MySQLDriver();
            default:
                throw new DatabaseConfigurationException($connectionData, "Driver for " . $connectionData->database . " is not implemented");
        }
    }

}