<?php

namespace siestaphp\generator;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use siestaphp\datamodel\validation\Validator;

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
     * @var Validator
     */
    protected $validator;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->errorCount = 0;
        $this->warningCount = 0;
        $this->validator = new Validator();
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
     * @return void
     */
    public function printValidationSummary()
    {
        $this->info($this->errorCount . " error(s)");
        $this->info($this->warningCount . " warning(s)");
    }

    /**
     * @param string $text
     *
     * @return void
     */
    public function info($text)
    {
        $this->logger->info($text);
    }

    /**
     * @param string $text
     * @param int $errorCode
     *
     * @return void
     */
    public function warn($text, $errorCode)
    {
        $this->warningCount++;
        $this->logger->warning($text, ["code" => $errorCode]);
    }

    /**
     * @param string $text
     * @param int $errorCode
     *
     * @return void
     */
    public function error($text, $errorCode)
    {
        $this->errorCount++;
        $this->logger->error($text, ["code" => $errorCode]);
    }

    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     *
     * @return void
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName, $errorCode)
    {
        if (!empty($needle)) {
            return;
        }
        $this->error("Mandatory attribute $attributeName in element <$elementName> not set", $errorCode);
    }


    /**
     * @param string $needle
     * @param string $message
     * @param int $errorCode
     *
     * @return void
     */
    public function errorIfNull($needle, $message, $errorCode)
    {
        if (!empty($needle)) {
            return;
        }
        $this->error($message, $errorCode);
    }

    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     *
     * @return void
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName, $errorCode)
    {
        if (in_array($needle, $haystack)) {
            return;
        }
        $allowedValues = implode(",", $haystack);
        $this->error("Attribute '$attributeName' in element <$elementName> has an invalid value ('$needle'). Allowed values are: $allowedValues", $errorCode);

    }

    /**
     * @param string $className
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfInvalidClassName($className, $attributeName, $elementName, $errorCode)
    {
        if ($this->validator->isValidClassName($className)) {
            return;
        }
        $this->error("Classname '$className' is an invalid php classname (element <$elementName> Attribute $attributeName)", $errorCode);
    }

    /**
     * @param string $namespace
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfInvalidNamespace($namespace, $attributeName, $elementName, $errorCode)
    {
        if ($this->validator->isValidNamespace($namespace)) {
            return;
        }

        $this->error("Namespace '$namespace' is an invalid php namespace (element <$elementName> Attribute $attributeName)", $errorCode);
    }

    /**
     * @param string $memberName
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfInvalidMemberName($memberName, $attributeName, $elementName, $errorCode)
    {
        if ($this->validator->isValidMemberName($memberName)) {
            return;
        }
        $this->error("Member '$memberName' is an invalid php variable name (element <$elementName> Attribute $attributeName)", $errorCode);
    }
}