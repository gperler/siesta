<?php

namespace siestaphp\driver\mysqli;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\Driver;
use siestaphp\driver\exceptions\CannotBeNullException;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\driver\exceptions\ForeignKeyConstraintFailedException;
use siestaphp\driver\exceptions\SQLException;
use siestaphp\driver\exceptions\TableAlreadyExistsException;
use siestaphp\driver\exceptions\TableDoesNotExistException;
use siestaphp\driver\exceptions\UniqueConstraintViolationException;
use siestaphp\driver\mysqli\installer\Installer;
use siestaphp\driver\mysqli\metadata\DatabaseMetaData;
use siestaphp\driver\ResultSet;
use siestaphp\driver\TableBuilder;

/**
 * Class MySQLDriver
 * @package siestaphp\driver\mysqli
 */
class MySQLDriver implements Driver
{
    const NAME = "mysql";

    const MYSQL_QUOTE = "`";

    /**
     * @param $name
     *
     * @return string
     */
    public static function quote($name)
    {
        return self::MYSQL_QUOTE . $name . self::MYSQL_QUOTE;
    }

    /**
     * @var \mysqli
     */
    private $connection;

    /**
     * @var string
     */
    private $database;

    /**
     *
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param {string} $host
     * @param {string} $port
     * @param {string} $database
     * @param {string} $user
     * @param {string} $password
     *
     * @throws ConnectException
     */
    public function connect($host, $port, $database, $user, $password)
    {

        $this->database = $database;
        // connect
        $this->connection = @new \mysqli ($host, $user, $password, $database, $port);

        // check for errors
        if ($this->connection->connect_errno) {
            throw new ConnectException ("Can't connect to " . $host . " : " . $this->connection->connect_error, $this->connection->connect_errno);
        }

        // switch character set
        $this->connection->set_charset('utf8');
        $this->connection->query("SET NAMES UTF8;");

    }

    /**
     * @param string $name
     */
    public function useDatabase($name)
    {
        $this->database = $name;
        $this->connection->query("USE " . $name);
    }

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function query($query)
    {
        $result = $this->connection->query($query);

        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }
        return new SimpleResultSet($result);
    }

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function multiQuery($query)
    {
        $result = $this->connection->multi_query($query);
        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }
        while ($this->connection->more_results()) {
            $this->connection->next_result();
            $this->connection->use_result();
        }

    }

    /**
     * @param string $query
     *
     * @return ResultSet
     */
    public function execute($query)
    {
        $result = $this->connection->query($query);

        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }

        while ($this->connection->more_results()) {
            $this->connection->next_result();
            $this->connection->use_result();
        }
        return $result;
    }

    /**
     * @param $query
     *
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     * @return ResultSet
     */
    public function executeStoredProcedure($query)
    {
        $result = $this->connection->multi_query($query);

        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }

        return new MultiQueryResultSet($this->connection);
    }

    /**
     * @param $errorNumber
     * @param $error
     * @param $sql
     *
     * @throws CannotBeNullException
     * @throws ForeignKeyConstraintFailedException
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     */
    private function handleQueryError($errorNumber, $error, $sql)
    {
        switch ($errorNumber) {
            case 1048:
                throw new CannotBeNullException($error, $errorNumber);
            case 1050:
                throw new TableAlreadyExistsException($error, $errorNumber);
            case 1062:
                throw new UniqueConstraintViolationException($error, $errorNumber);
            case 1146:
                throw new TableDoesNotExistException($error, $errorNumber);
            case 1451:
                throw new ForeignKeyConstraintFailedException($error, $errorNumber);
            default:
                throw new SQLException($error, $errorNumber);
        }
    }

    /**
     * @return TableBuilder
     */
    public function getTableBuilder()
    {

        return new MySQLTableBuilder();
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function escape($value)
    {
        return $this->connection->real_escape_string($value);
    }

    /**
     * installs the installer
     */
    public function install()
    {
        Installer::install($this);
    }

    /**
     * @param string $technicalName
     *
     * @return int
     */
    public function getSequence($technicalName)
    {
        $result = $this->executeStoredProcedure("CALL `SEQUENCER_GETSEQUENCE`('$technicalName')");

        $sequence = null;
        while ($result->hasNext()) {
            $sequence = $result->getIntegerValue("@sequence:=SEQUENCER.SEQ");
        }
        $result->close();

        return ($sequence) ? $sequence : 1;
    }

    public function startTransaction()
    {
        $this->connection->autocommit(false);

    }

    public function commit()
    {
        $this->connection->commit();
        $this->connection->autocommit(true);
    }

    public function rollback()
    {
        $this->connection->rollback();
        $this->connection->autocommit(true);
    }

    public function enableForeignKeyChecks()
    {
        $this->execute("set foreign_key_checks=1");
    }

    public function disableForeignKeyChecks()
    {
        $this->execute("set foreign_key_checks=0");
    }


    /**
     * @param string $databaseName
     * @param string $targetNamespace
     * @param string $targetPath
     *
     * @return EntitySource[]
     */
    public function getEntitySourceList($databaseName, $targetNamespace, $targetPath) {
        $databaseMetaData = new DatabaseMetaData($this);
        return $databaseMetaData->getEntitySourceList($databaseName,$targetNamespace, $targetPath);
    }

}

