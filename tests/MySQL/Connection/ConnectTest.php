<?php

namespace SiestaTest\Functional\MySQL\Connection;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;

class MySQLConnectTest extends Unit
{

    public function testConnection()
    {

        $connection = ConnectionFactory::getInstance()->getConnection();
        $this->assertNotNull($connection);

        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);
    }

}