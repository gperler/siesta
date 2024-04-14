<?php
declare(strict_types=1);

namespace Siesta\Logger;

use Psr\Log\LoggerInterface;

/**
 * @author Gregor Müller
 */
class NullLogger implements LoggerInterface
{
    public function emergency($message, array $context = array()): void
    {
    }

    public function alert($message, array $context = array()): void
    {
    }

    public function critical($message, array $context = array()): void
    {
    }

    public function error($message, array $context = array()): void
    {
    }

    public function warning($message, array $context = array()): void
    {
    }

    public function notice($message, array $context = array()): void
    {
    }

    public function info($message, array $context = array()): void
    {
    }

    public function debug($message, array $context = array()): void
    {
    }

    public function log($level, $message, array $context = array()): void
    {
    }

}