<?php
// This is global bootstrap for autoloading
date_default_timezone_set('Europe/Berlin');

require_once 'vendor/autoload.php';

// configure database
\siestaphp\Config::getInstance(__DIR__ . "/siesta.test.config.json");