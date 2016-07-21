<?php

namespace SiestaTest\Functional\MySQL\Connection;

use Siesta\Config\Config;
use Siesta\Database\ConnectionFactory;

class MySQLConnectTest extends \PHPUnit_Framework_TestCase
{

    public function testConnection()
    {
        
        $connection = ConnectionFactory::getInstance()->getConnection();
        $this->assertNotNull($connection);

        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);
    }

}