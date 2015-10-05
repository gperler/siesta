<?php


namespace siestaphp\driver;

use siestaphp\driver\mysqli\MySQLDriver;

/**
 * Class DriverFactory
 * @package siestaphp\driver
 */
class DriverFactory
{

    private static $driver;

    /**
     * @param null $config
     *
     * @return MySQLDriver
     * @throws exceptions\ConnectException
     */
    public static function getDriver($config = null)
    {
        if (!self::$driver) {
            self::$driver = new MySQLDriver();
            if ($config) {
                self::$driver->connect($config["host"], $config["port"], $config["database"], $config["user"], $config["password"]);
            } else {
                self::$driver->connect('127.0.0.1', 3306, 'siestajs', 'root', '');
            }

        }
        return self::$driver;
    }

}