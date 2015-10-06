<?php
// This is global bootstrap for autoloading
date_default_timezone_set('Europe/Berlin');

require_once 'vendor/autoload.php';

define(SIESTA_DATABASE, "SIESTA_TEST");
define(SIESTA_DATABASE_HOST, "127.0.0.1");
define(SIESTA_DATABASE_PORT, 3306);
define(SIESTA_DATABASE_USER, "root");
define(SIESTA_DATABASE_PASSWORD, "");


\siestaphp\runtime\ServiceLocator::getDriver(array(
    "user" => SIESTA_DATABASE_USER,
    "password" => SIESTA_DATABASE_PASSWORD,
    "port" => SIESTA_DATABASE_PORT,
    "database" => null,
    "host" => SIESTA_DATABASE_HOST
));