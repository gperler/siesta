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
     * @return bool
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
    public function close(): void;

    /**
     * @param string $key
     *
     * @return bool|null
     */
    public function getBooleanValue(string $key): ?bool;

    /**
     * @param string $key
     *
     * @return integer|null
     */
    public function getIntegerValue(string $key): ?int;

    /**
     * @param string $key
     *
     * @return float|null
     */
    public function getFloatValue(string $key): ?float;

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getStringValue(string $key): ?string;

    /**
     * @param string $key
     *
     * @return SiestaDateTime|null
     */
    public function getDateTime(string $key): ?SiestaDateTime;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getObject(string $key): mixed;

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key): ?array;
}