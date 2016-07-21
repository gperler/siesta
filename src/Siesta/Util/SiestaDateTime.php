<?php
declare(strict_types = 1);

namespace Siesta\Util;

/**
 * @author Gregor Müller
 */
class SiestaDateTime extends \DateTime
{
    /**
     * @return string
     */
    function getJSONDateTime() : string
    {

        return $this->format("Y-m-d\\TH:i:s");
    }

    /**
     * @return string
     */
    public function getSQLDateTime() : string
    {
        return $this->format("Y-m-d H:i:s");
    }

    /**
     * @return string
     */
    public function getSQLDate() : string
    {
        return $this->format("Y-m-d");
    }

    /**
     * @return string
     */
    public function getSQLTime() : string
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