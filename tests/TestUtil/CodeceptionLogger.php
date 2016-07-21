<?php

namespace SiestaTest\TestUtil;

use Codeception\Util\Debug;
use Psr\Log\LoggerInterface;
use Siesta\Util\ArrayUtil;

/**
 * Class CodeceptionLogger
 * @package siestaphp\tests\functional\xmlreader
 */
class CodeceptionLogger implements LoggerInterface
{
    /**
     * @var int[]
     */
    protected $errorCodeList;

    /**
     * @var int[]
     */
    protected $warningCodeList;

    /**
     * @var bool
     */
    protected $silent;

    /**
     * @param bool $silent
     */
    public function __construct($silent = false)
    {
        $this->errorCodeList = array();
        $this->warningCodeList = array();
        $this->silent = $silent;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return sizeof($this->errorCodeList) !== 0;
    }

    /**
     * @param $errorCode
     *
     * @return bool
     */
    public function isErrorCodeSet($errorCode)
    {
        return in_array($errorCode, $this->errorCodeList);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }

    }

    /**
     * Action must be taken immediately.
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function alert($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

    /**
     * Critical conditions.
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function critical($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function error($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug("<error>" . $message . "</error>");
        }

        $code = ArrayUtil::getFromArray($context, "code");
        if ($code) {
            $this->errorCodeList[] = $code;
        }

    }

    /**
     * Exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function warning($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function notice($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

    /**
     * Interesting events.
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function info($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function debug($message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        if (!$this->silent) {
            Debug::debug($message);
        }
    }

}