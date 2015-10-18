<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionFactory;
use siestaphp\generator\Generator;
use siestaphp\util\File;

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


    protected function connectAndInstall()
    {
        $this->connection = ConnectionFactory::getConnection();
        $this->connection->query("DROP DATABASE IF EXISTS " . $this->connection->getDatabase());
        $this->connection->query("CREATE DATABASE " . $this->connection->getDatabase());
        $this->connection->useDatabase($this->connection->getDatabase());
    }

    protected function dropDatabase()
    {
        if ($this->connection) {
            $this->connection->query("DROP DATABASE IF EXISTS " . $this->connection->getDatabase());
        }
    }

    /**
     * @param string $assetPath
     * @param string $srcXML
     */
    protected function generateEntityFile($assetPath, $srcXML)
    {
        $this->logger = new CodeceptionLogger();
        $generator = new Generator($this->logger);
        $generator->generateFile(__DIR__ . $assetPath, __DIR__ . $assetPath . $srcXML);

    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    protected function loadJSON($fileName)
    {
        $file = new File($fileName);
        $this->assertTrue($file->exists(), "File " . $fileName . " does not exist");
        return $file->loadAsJSONArray();
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