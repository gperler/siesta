<?php

namespace siestaphp\driver;

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
    public $postConnectStatements;

    /**
     * @var string
     */
    public $charSet;

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
    public function __construct($name = null, $driver = null, $host = null, $port = null, $database = null, $user = null, $password = null, $charSet = null, array $postConnectStatements = null) {
        $this->postConnectStatements = $postConnectStatements ? $postConnectStatements : array();
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
     * @param string $statement
     */
    public function addPostConnectStatement($statement) {
        $this->postConnectStatements[] = $statement;
    }
}