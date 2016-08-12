<?php
// Here you can initialize variables that will be available to your tests

namespace SiestaTest\End2End;

require_once 'vendor/autoload.php';

use Siesta\Config\Config;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\NamingStrategy\NoTransformStrategy;

Config::getInstance("siesta.test.mysql.config.json");

NamingStrategyRegistry::setColumnNamingStrategy(new NoTransformStrategy());