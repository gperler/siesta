<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionFactory;

/**
 * Class SiestaTester
 * @package siestaphp\tests\functional
 */
class SiestaTester extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Connection
     */
    protected $connection;

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
        //$d = SIESTA_DATABASE;
        $this->connection = ConnectionFactory::getConnection();
        $this->connection->query("DROP DATABASE IF EXISTS " . $d);
        $this->connection->query("CREATE DATABASE " . $d);
        $this->connection->useDatabase($d);
        $this->connection->install();
        $this->databaseName = $d;
    }

    protected function dropDatabase()
    {
        if ($this->connection) {
            $this->connection->query("DROP DATABASE IF EXISTS " . $this->databaseName);
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
        $this->logger->printValidationSummary();
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