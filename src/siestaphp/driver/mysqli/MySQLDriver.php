<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 12.10.15
 * Time: 16:51
 */

namespace siestaphp\driver\mysqli;

use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionData;
use siestaphp\driver\Driver;
use siestaphp\driver\exceptions\ConnectException;

/**
 * Class MySQLDriver
 * @package siestaphp\driver\mysqli
 */
class MySQLDriver implements Driver
{
    const MYSQL_QUOTE = "`";

    /**
     * @param $name
     *
     * @return string
     */
    public static function quote($name)
    {
        return self::MYSQL_QUOTE . $name . self::MYSQL_QUOTE;
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @return Connection
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData)
    {
        return new MySQLConnection($connectionData);
    }

}