<?php

namespace siestaphp\tests\unit\connectionFactory;

use siestaphp\Config;
use siestaphp\driver\ConnectionFactory;

/**
 * Class ConfigTest
 */
class ConnectionFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $connectionName
     *
     * @return TestConnection
     */
    protected function getConnectionDetails($connectionName)
    {
        return ConnectionFactory::getConnection($connectionName);
    }

    public function testConnectionFactory()
    {
        ConnectionFactory::reset();
        Config::reset();

        $config = Config::getInstance("test.config.json");
        $this->assertNotNull($config, "config not found with name");

        ConnectionFactory::getInstance();

        $connection = $this->getConnectionDetails("default");
        $this->assertNotNull($connection, "Connection not retrieved");

        $connection = $this->getConnectionDetails(null);
        $this->assertNotNull($connection, "Connection not retrieved");

        $this->assertSame("default", $connection->connectionData->name, "name not correct");
        $this->assertSame("siestaphp\\tests\\unit\\connectionFactory\\TestDriver", $connection->connectionData->driver, "driver not correct");
        $this->assertSame(true, $connection->connectionData->isDefault, "isDefault not correct");
        $this->assertSame("127.0.0.1", $connection->connectionData->host, "host not correct");
        $this->assertSame(3306, $connection->connectionData->port, "port not correct");
        $this->assertSame("SIESTA_TEST", $connection->connectionData->database, "database not correct");
        $this->assertSame("user", $connection->connectionData->user, "user not correct");
        $this->assertSame("password", $connection->connectionData->password, "password not correct");
        $this->assertSame("SET NAMES UTF8", $connection->connectionData->postConnectStatementList[0], "postConnectStatementList not correct");

    }

}
