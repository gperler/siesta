<?php

declare(strict_types=1);

namespace Siesta\Util;

class StopWatch
{
    private static $startTime;


    /**
     *
     */
    public static function start()
    {
        self::$startTime = microtime(true);
    }


    /**
     * @param string $text
     * @param int $indent
     */
    public static function echoTime(string $text, int $indent = 0)
    {
        $indent = '';
        if ($indent !== 0) {

        }

        $delta = (microtime(true) - self::$startTime) * 1000;
        echo $text . ' > ' . $delta . PHP_EOL;
    }
}