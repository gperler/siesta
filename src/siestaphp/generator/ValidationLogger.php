<?php

namespace siestaphp\generator;

use Codeception\Util\Debug;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ValidationLogger
 * @package siestaphp\generator
 */
class ValidationLogger implements LoggerAwareInterface
{

    /**
     * @var int
     */
    protected $errorCount;

    /**
     * @var int
     */
    protected $warningCount;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->errorCount = 0;
        $this->warningCount = 0;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->errorCount !== 0;
    }

    /**
     *
     */
    public function printValidationSummary()
    {
        $this->info($this->errorCount . " error(s)");
        $this->info($this->warningCount . " warning(s)");
    }

    /**
     * @param string $text
     */
    public function info($text)
    {
        $this->logger->info($text);
    }

    /**
     * @param string $text
     * @param int $errorCode
     */
    public function warn($text, $errorCode)
    {
        $this->warningCount++;
        $this->logger->warning($text, array("code" => $errorCode));
    }

    /**
     * @param string $text
     * @param int $errorCode
     */
    public function error($text, $errorCode)
    {
        $this->errorCount++;
        $this->logger->error($text, array("code" => $errorCode));
    }

    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName, $errorCode)
    {
        if (!$needle) {
            $this->error("Mandatory attribute $attributeName in element <$elementName> not set", $errorCode);

        }
    }

    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName, $errorCode)
    {
        if (!in_array($needle, $haystack)) {
            $allowedValues = implode(",", $haystack);
            $this->error("Attribute '$attributeName' in element <$elementName> has an invalid value ('$needle'). Allowed values are: $allowedValues", $errorCode);
        }
    }

}