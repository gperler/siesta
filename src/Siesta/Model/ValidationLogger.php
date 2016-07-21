<?php
declare(strict_types = 1);

namespace Siesta\Model;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Siesta\Logger\NullLogger;

/**
 * @author Gregor Müller
 */
class ValidationLogger implements LoggerAwareInterface
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var int
     */
    protected $errorCount;

    /**
     * @var int[]
     */
    protected $errorCodeList;

    /**
     * @var int
     */
    protected $warningCount;

    /**
     * @var int[]
     */
    protected $warningCodeList;

    /**
     * ValidationLogger constructor.
     */
    public function __construct()
    {
        $this->errorCodeList = [];
        $this->errorCount = 0;
        $this->warningCodeList = [];
        $this->warningCount = 0;
        $this->logger = new NullLogger();
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
    public function hasError() : bool
    {
        return $this->errorCount !== 0;
    }

    /**
     * @return int
     */
    public function getErrorCount() : int
    {
        return $this->errorCount;
    }

    /**
     * @return int[]
     */
    public function getErrorCodeList() : array
    {
        return $this->errorCodeList;
    }

    /**
     * @param int $code
     *
     * @return bool
     */
    public function hasErrorCode(int $code) : bool
    {
        return in_array($code, $this->errorCodeList);
    }

    /**
     * @return bool
     */
    public function hasWarning() : bool
    {
        return $this->warningCount !== 0;
    }

    /**
     * @return int
     */
    public function getWarningCount() : int
    {
        return $this->warningCount;
    }

    /**
     * @return array
     */
    public function getWarningCodeList() : array
    {
        return $this->warningCodeList;
    }

    /**
     * @param int $code
     *
     * @return bool
     */
    public function hasWarningCode(int $code) : bool
    {
        return in_array($code, $this->warningCodeList);
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
    public function info(string $text)
    {
        $this->logger->info($text);
    }

    /**
     * @param string $text
     * @param int $warningCode
     */
    public function warn(string $text, int $warningCode)
    {
        $this->warningCodeList[] = $warningCode;
        $this->warningCount++;
        $this->logger->warning($text);
    }

    /**
     * @param string $text
     * @param int $errorCode
     *
     * @return void
     */
    public function error(string $text, int $errorCode)
    {
        $this->errorCodeList[] = $errorCode;
        $this->errorCount++;
        $this->logger->error($text);
    }

}