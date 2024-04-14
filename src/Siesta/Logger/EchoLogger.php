<?php
declare(strict_types=1);

namespace Siesta\Logger;

use Psr\Log\LoggerInterface;

/**
 * @author Gregor Müller
 */
class EchoLogger implements LoggerInterface
{
    public function emergency($message, array $context = array()): void
    {
        echo "[emergency] " . $message . PHP_EOL;
    }

    public function alert($message, array $context = array()): void
    {
        echo "[alert] " . $message . PHP_EOL;
    }

    public function critical($message, array $context = array()): void
    {
        echo "[critical] " . $message . PHP_EOL;
    }

    public function error($message, array $context = array()): void
    {
        echo "[error] " . $message . PHP_EOL;
    }

    public function warning($message, array $context = array()): void
    {
        echo "[warning] " . $message . PHP_EOL;
    }

    public function notice($message, array $context = array()): void
    {
        echo "[notice] " . $message . PHP_EOL;
    }

    public function info($message, array $context = array()): void
    {
        echo "[info] " . $message . PHP_EOL;
    }

    public function debug($message, array $context = array()): void
    {
        echo "[debug] " . $message . PHP_EOL;
    }

    public function log($level, $message, array $context = array()): void
    {
        echo "[$level] " . $message . PHP_EOL;
    }

}