<?php
// This is global bootstrap for autoloading
date_default_timezone_set('Europe/Berlin');

require_once 'vendor/autoload.php';

// connection Details
$name = "default";
$driver = "mysql";
$host = "127.0.0.1";
$port = 3306;
$database = "SIESTA_TEST";
$user = "root";
$password = "";
$charset = "utf8";
$postConnectStatementList = array("SET NAMES UTF8");

$cd = new \siestaphp\driver\ConnectionData($name, $driver, $host, $port, $database, $user, $password, $charset, $postConnectStatementList);
\siestaphp\driver\ConnectionFactory::addConnection($cd);