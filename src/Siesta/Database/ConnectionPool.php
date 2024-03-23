<?php

declare(strict_types=1);

namespace Siesta\Database;

use Siesta\Exception\InvalidConfigurationException;
use Siesta\Util\ArrayUtil;

class ConnectionPool
{

    const EXCEPTION_NO_DEFAULT = "No default connection available";

    const EXCEPTION_NOT_CONFIGURED = "Connection with name %s is not configured";

    const EXCEPTION_DRIVER_NOT_IMPLEMENTED = "Driver for %s is not implemented";

    /**
     * @var Driver[]
     */
    protected array $driverList;

    /**
     * @var Connection[]
     */
    protected array $connectionList;

    /**
     * @var Connection|null
     */
    protected ?Connection $defaultConnection;

    /**
     * @var ConnectionData[]
     */
    protected array $connectionDataList;

    /**
     * ConnectionPool constructor.
     */
    public function __construct()
    {
        $this->driverList = [];
        $this->connectionList = [];
        $this->connectionDataList = [];
        $this->defaultConnection = null;
    }

    /**
     * @param ConnectionData $connectionData
     */
    public function addConnection(ConnectionData $connectionData): void
    {
        $connection = ArrayUtil::getFromArray($this->connectionList, $connectionData->name);
        if ($connection !== null) {
            return;
        }

        $driver = $this->getDriver($connectionData);
        $connection = $driver->connect($connectionData);

        $this->connectionList[$connectionData->name] = $connection;
        if ($this->defaultConnection === null or $connectionData->isDefault) {
            $this->defaultConnection = $connection;
        }
    }

    /**
     * @param $name
     *
     * @return Connection
     * @throws InvalidConfigurationException
     */
    public function getConnection($name = null): Connection
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
    public function getDefaultConnection(): Connection
    {
        if ($this->defaultConnection === null) {
            throw new InvalidConfigurationException(self::EXCEPTION_NO_DEFAULT);
        }
        return $this->defaultConnection;
    }

    /**
     *
     */
    public function closeAll(): void
    {
        if ($this->defaultConnection !== null) {
            $this->defaultConnection->close();
        }

        foreach ($this->connectionList as $connection) {
            $connection->close();
        }
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Driver
     * @throws InvalidConfigurationException
     */
    protected function getDriver(ConnectionData $connectionData): Driver
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
    protected function instantiateDriver(ConnectionData $connectionData): Driver
    {
        $class = $connectionData->driver;

        if (!class_exists($class)) {
            throw new InvalidConfigurationException(sprintf(self::EXCEPTION_DRIVER_NOT_IMPLEMENTED, $connectionData->driver));
        }
        return new $class;

    }

}