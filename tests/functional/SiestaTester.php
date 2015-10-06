<?php

namespace siestaphp\tests\functional;

/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 02.10.15
 * Time: 20:47
 */
class SiestaTester extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \siestaphp\driver\Driver
     */
    protected $driver;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var float
     */
    protected $startTime;

    /**
     * @param string $database
     */
    protected function connectAndInstall($database)
    {
        $d = $database;
        $d = SIESTA_DATABASE;

        $this->driver = \siestaphp\runtime\ServiceLocator::getDriver();
        $this->driver->query("DROP DATABASE IF EXISTS " . $d);
        $this->driver->query("CREATE DATABASE " . $d);
        $this->driver->useDatabase($d);
        $this->driver->install();
        $this->databaseName = $d;
    }

    protected function dropDatabase()
    {
        if ($this->driver) {
            $this->driver->query("DROP DATABASE IF EXISTS " . $this->databaseName);
        }
    }

    /**
     * @param string $assetPath
     * @param string $srcXML
     */
    protected function generateEntityFile($assetPath, $srcXML)
    {
        $generator = new \siestaphp\generator\Generator();
        $generator->generateFile(__DIR__ . $assetPath, __DIR__ . $assetPath . $srcXML);

    }

    protected function startTimer()
    {
        $this->startTime = -microtime(true);
    }

    /**
     * @param $output
     * @param int $executionCount
     */
    protected function stopTimer($output, $executionCount = 0)
    {
        $delta = ($this->startTime + microtime(true)) * 1000;
        if ($executionCount) {
            $delta /= $executionCount;
        }
        \Codeception\Util\Debug::debug(sprintf($output, $delta));
    }
}