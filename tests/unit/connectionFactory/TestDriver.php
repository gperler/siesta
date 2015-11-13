<?php

namespace siestaphp\tests\unit\connectionFactory;

use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionData;
use siestaphp\driver\Driver;
use siestaphp\driver\exceptions\ConnectException;

/**
 * Class TestDriver
 * @package siestaphp\tests\unit\connectionFactory
 */
class TestDriver implements Driver
{
    /**
     * @param ConnectionData $connectionData
     *
     * @return Connection
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData)
    {
        return new TestConnection($connectionData);
    }

}