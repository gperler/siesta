<?php
declare(strict_types=1);

namespace Siesta\Util;

use DateTime;
use DateTimeZone;
use Exception;
use RuntimeException;

/**
 * @author Gregor Müller
 */
class SiestaDateTime extends DateTime
{

    /**
     * SiestaDateTime constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        try {
            parent::__construct($time, $timezone);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

    }

    /**
     * @return string
     */
    function getJSONDateTime(): string
    {

        return $this->format("Y-m-d\\TH:i:s");
    }

    /**
     * @return string
     */
    public function getSQLDateTime(): string
    {
        return $this->format("Y-m-d H:i:s");
    }

    /**
     * @return string
     */
    public function getSQLDate(): string
    {
        return $this->format("Y-m-d");
    }

    /**
     * @return string
     */
    public function getSQLTime(): string
    {
        return $this->format("H:i:s");
    }

    /**
     * @param string $dateString
     *
     * @return void
     */
    public function stringToTime(string $dateString)
    {
        $this->setTimestamp(strtotime($dateString));
    }

    /**
     * @param SiestaDateTime $dateTime
     *
     * @return bool
     */
    public function equals(SiestaDateTime $dateTime)
    {
        return ($this->getTimestamp() === $dateTime->getTimestamp());
    }
}