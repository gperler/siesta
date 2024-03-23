<?php

declare(strict_types=1);

namespace Siesta\Database;

use Siesta\Database\Exception\ConnectException;
use Siesta\Exception\InvalidConfigurationException;

/**
 * @author Gregor Müller
 */
class ConnectionFactory
{
    /**
     * @var ConnectionPool|null
     */
    private static ?ConnectionPool $connectionPool = null;

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     * @throws InvalidConfigurationException
     */
    public static function addConnection(ConnectionData $connectionData): void
    {
        self::getInstance()->addConnection($connectionData);
    }

    /**
     * @param string|null $name
     *
     * @return Connection
     */
    public static function getConnection(string $name = null): Connection
    {
        return self::getInstance()->getConnection($name);
    }

    /**
     * @return ConnectionPool
     */
    public static function getInstance(): ConnectionPool
    {
        if (self::$connectionPool === null) {
            self::$connectionPool = new ConnectionPool();
        }
        return self::$connectionPool;
    }

    /**
     *
     */
    public static function closeAll(): void
    {
        self::getInstance()->closeAll();
    }

}

