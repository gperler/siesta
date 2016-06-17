<?php

namespace siestaphp\runtime;

/**
 * Class SiestaDateTime
 * @package siestaphp\runtime
 */
class SiestaDateTime extends \DateTime
{
    /**
     * @return string
     */
    function getJSONDateTime()
    {

        return $this->format("Y-m-d\\TH:i:s");
    }

    /**
     * @return string
     */
    public function getSQLDateTime()
    {
        return $this->format("Y-m-d H:i:s");
    }

    /**
     * @return string
     */
    public function getSQLDate()
    {
        return $this->format("Y-m-d");
    }

    /**
     * @return string
     */
    public function getSQLTime()
    {
        return $this->format("H:i:s");
    }

    /**
     * @param string $dateString
     *
     * @return void
     */
    public function stringToTime($dateString)
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