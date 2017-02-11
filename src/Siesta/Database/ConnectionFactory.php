<?php

declare(strict_types = 1);

namespace Siesta\Database;

use Siesta\Database\Exception\ConnectException;
use Siesta\Exception\InvalidConfigurationException;

/**
 * @author Gregor Müller
 */
class ConnectionFactory
{
    /**
     * @var ConnectionPool
     */
    private static $connectionPool;

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws InvalidConfigurationException
     */
    public static function addConnection(ConnectionData $connectionData)
    {
        self::getInstance()->addConnection($connectionData);
    }

    /**
     * @param string $name
     *
     * @return Connection
     * @throws InvalidConfigurationException
     */
    public static function getConnection(string $name = null) : Connection
    {
        return self::getInstance()->getConnection($name);
    }

    /**
     * @return ConnectionPool
     */
    public static function getInstance() : ConnectionPool
    {
        if (!self::$connectionPool) {

            self::$connectionPool = new ConnectionPool();
        }
        return self::$connectionPool;
    }

    /**
     *
     */
    public static function closeAll()
    {
        self::getInstance()->closeAll();
    }

}

