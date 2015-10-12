#!/usr/bin/env php
<?php

require_once 'vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new \siestaphp\console\GeneratorCommand('build'));
$app->add(new \siestaphp\console\ReverseGeneratorCommand('reverse'));
$app->add(new \siestaphp\console\InitCommand('init'));

$app->run();