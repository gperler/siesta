<?php


namespace siestaphp\driver;

use siestaphp\driver\exceptions\ConnectException;

/**
 * Interface Driver
 * @package siestaphp\driver
 */
interface Driver
{

    /**
     * returns the current database
     * @return string
     */
    public function getDatabase();

    /**
     * connects to the given database
     *
     * @param {string} $host
     * @param {string} $port
     * @param {string} $database
     * @param {string} $user
     * @param {string} $password
     *
     * @throws ConnectException
     */
    public function connect($host, $port, $database, $user, $password);

    /**
     * @param string $name
     */
    public function useDatabase($name);

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function query($query);

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function multiQuery($query);

    /**
     * @param $query
     *
     * @return \mysqli_result
     */
    public function execute($query);

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function executeStoredProcedure($query);

    /**
     * @return TableBuilder
     */
    public function getTableBuilder();

    /**
     * @param string $value
     *
     * @return string
     */
    public function escape($value);

    /**
     * allows the driver to install needed artifacts. Invoked when generating entities
     */
    public function install();

    /**
     * returns next sequence for given technical name
     *
     * @param string $technicalName
     *
     * @return int
     */
    public function getSequence($technicalName);

    public function startTransaction();

    public function commit();

    public function rollback();

    public function enableForeignKeyChecks();

    public function disableForeignKeyChecks();

}