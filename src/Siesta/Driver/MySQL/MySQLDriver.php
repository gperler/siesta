<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\Connection;
use Siesta\Database\ConnectionData;
use Siesta\Database\Driver;
use Siesta\Database\Exception\ConnectException;

/**
 * @author Gregor Müller
 */
class MySQLDriver implements Driver
{
    const MYSQL_QUOTE = "`";

    const MYSQL_DRIVER_NAME = "mysql";

    const DRIVER_CLASS = 'Siesta\Driver\MySQL\MySQLDriver';

    /**
     * @param string $name
     *
     * @return string
     */
    public static function quote(string $name) : string
    {
        return self::MYSQL_QUOTE . $name . self::MYSQL_QUOTE;
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Connection
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData) : Connection
    {
        return new MySQLConnection($connectionData);
    }

}