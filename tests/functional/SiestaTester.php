<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;

/**
 * Class SiestaTester
 * @package siestaphp\tests\functional
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
     * @var CodeceptionLogger
     */
    protected $logger;

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
        $this->logger = new CodeceptionLogger();
        $generator = new \siestaphp\generator\Generator($this->logger);
        $generator->generateFile(__DIR__ . $assetPath, __DIR__ . $assetPath . $srcXML);

    }

    /**
     * checks if the validation has errors
     */
    protected function assertNoValidationErrors()
    {
        if (!$this->logger) {
            return;
        }
        $this->assertFalse($this->logger->hasErrors(), "Expected no validation errors");
    }

    protected function startTimer()
    {
        $this->startTime = -microtime(true);
    }

    /**
     * @param string $output
     * @param int $executionCount
     */
    protected function stopTimer($output, $executionCount = 0)
    {
        $delta = ($this->startTime + microtime(true)) * 1000;
        if ($executionCount) {
            $delta /= $executionCount;
        }
        Debug::debug(sprintf($output, $delta));
    }
}