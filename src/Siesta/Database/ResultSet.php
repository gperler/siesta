<?php
declare(strict_types = 1);
namespace Siesta\Database;

use Siesta\Util\SiestaDateTime;

/**
 * @author Gregor Müller
 */
interface ResultSet
{

    /**
     * checks if there are more ResultSets
     * @return boolean
     */
    public function hasNext() : bool;

    /**
     * gets the next ResultSet
     * @return array
     */
    public function getNext() : array;

    /**
     * closes the database connection
     */
    public function close();

    /**
     * @param string $key
     *
     * @return boolean|null
     */
    public function getBooleanValue(string $key);

    /**
     * @param string $key
     *
     * @return integer|null
     */
    public function getIntegerValue(string $key);

    /**
     * @param string $key
     *
     * @return float|null
     */
    public function getFloatValue(string $key);

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getStringValue(string $key);

    /**
     * @param string $key
     *
     * @return SiestaDateTime
     */
    public function getDateTime(string $key);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getObject(string $key);

    /**
     * @param string $key
     *
     * @return array
     */
    public function getArray(string $key);
}