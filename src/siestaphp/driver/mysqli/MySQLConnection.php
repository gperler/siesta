<?php

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionData;
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
 * Class MySQLConnection
 * @package siestaphp\driver\mysqli
 */
class MySQLConnection implements Connection
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
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     */
    public function __construct(ConnectionData $connectionData) {
        $this->connect($connectionData);
    }

    /**
     * @param ConnectionData $connectionData
     *
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData)
    {

        $this->database = $connectionData->database;
        // connect
        $this->connection = @new \mysqli ($connectionData->host, $connectionData->user, $connectionData->password, $connectionData->database, $connectionData->port);

        // check for errors
        if ($this->connection->connect_errno) {
            throw new ConnectException ("Can't connect to " . $connectionData->host . " : " . $this->connection->connect_error, $this->connection->connect_errno);
        }

        // switch character set
        if ($connectionData->charSet) {
            $this->connection->set_charset($connectionData->charSet);
        }

        $pcs = implode(";", $connectionData->postConnectStatements);
        if($pcs) {
            $this->multiQuery($pcs);
        }

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
    public function getEntitySourceList($databaseName, $targetNamespace, $targetPath)
    {
        $databaseMetaData = new DatabaseMetaData($this);
        return $databaseMetaData->getEntitySourceList($databaseName, $targetNamespace, $targetPath);
    }

}

