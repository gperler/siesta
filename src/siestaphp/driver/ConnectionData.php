<?php

namespace siestaphp\driver;

use siestaphp\util\Util;

/**
 * Class ConnectionData
 * @package siestaphp\driver
 */
class ConnectionData
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $driver;

    /**
     * @var string
     */
    public $host;

    /**
     * @var int
     */
    public $port;

    /**
     * @var string
     */
    public $database;

    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string[]
     */
    public $postConnectStatementList;

    /**
     * @var string
     */
    public $charSet;

    /**
     * @var bool
     */
    public $isDefault;

    /**
     * @param $name
     * @param $driver
     * @param $host
     * @param $port
     * @param $database
     * @param $user
     * @param $password
     * @param $charSet
     * @param array|null $postConnectStatements
     */
    public function __construct($name = null, $driver = null, $host = null, $port = null, $database = null, $user = null, $password = null, $charSet = null, array $postConnectStatements = null)
    {
        $this->postConnectStatementList = $postConnectStatements ? $postConnectStatements : [];
        $this->name = $name;
        $this->driver = $driver;
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->charSet = $charSet;
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values) {
        $this->name = Util::getFromArray($values, "name");
        $this->driver = Util::getFromArray($values, "driver");
        $this->host = Util::getFromArray($values, "host");
        $this->port = Util::getFromArray($values, "port");
        $this->database = Util::getFromArray($values, "database");
        $this->user = Util::getFromArray($values, "user");
        $this->password = Util::getFromArray($values, "password");
        $this->charSet = Util::getFromArray($values, "charSet");
        $this->isDefault = Util::getFromArray($values, "isDefault");
        $this->postConnectStatementList = Util::getFromArray($values, "postConnectStatementList" );
    }

    /**
     * @param string $statement
     */
    public function addPostConnectStatement($statement)
    {
        $this->postConnectStatementList[] = $statement;
    }

    /**
     * @return string
     */
    public function __toString() {
        return implode(PHP_EOL, [
            "Name " . $this->name,
            "Driver " . $this->driver,
            "Host " . $this->host,
            "Port " . $this->port,
            "Database " . $this->database,
            "User " . $this->user,
            "Charset " . $this->user,
            "Post Connect statement " . implode(";", $this->postConnectStatementList)
        ]);
    }
}