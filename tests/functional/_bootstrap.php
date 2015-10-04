<?php
// Here you can initialize variables that will be available to your tests

date_default_timezone_set('Europe/Berlin');


\siestaphp\runtime\ServiceLocator::getDriver(array(
    "user" => SIESTA_DATABASE_USER,
    "password" => SIESTA_DATABASE_PASSWORD,
    "port" => SIESTA_DATABASE_PORT,
    "database" => null,
    "host" => SIESTA_DATABASE_HOST
));


