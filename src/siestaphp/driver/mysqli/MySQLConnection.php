<?php

namespace siestaphp\driver\mysqli;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ColumnMigrator;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionData;
use siestaphp\driver\CreateStatementFactory;
use siestaphp\driver\exceptions\CannotBeNullException;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\driver\exceptions\ForeignKeyConstraintFailedException;
use siestaphp\driver\exceptions\SQLException;
use siestaphp\driver\exceptions\TableAlreadyExistsException;
use siestaphp\driver\exceptions\TableDoesNotExistException;
use siestaphp\driver\exceptions\UniqueConstraintViolationException;
use siestaphp\driver\mysqli\metadata\DatabaseMetaData;
use siestaphp\driver\ResultSet;

/**
 * Class MySQLConnection
 * @package siestaphp\driver\mysqli
 */
class MySQLConnection implements Connection
{
    const NAME = "mysql";

    /**
     * @param $name
     *
     * @return string
     */
    public static function quote($name)
    {
        return MySQLDriver::quote($name);
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
    public function __construct(ConnectionData $connectionData)
    {
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

        // database does not exist . create it
        if ($this->connection->connect_errno === 1049) {
            $this->connection = @new \mysqli ($connectionData->host, $connectionData->user, $connectionData->password, null, $connectionData->port);
            $this->useDatabase($connectionData->database);
        }

        // check for errors
        if ($this->connection->connect_errno) {
            throw new ConnectException ("Can't connect to " . $connectionData->host . " : " . $this->connection->connect_error, $this->connection->connect_errno);
        }

        // switch character set
        if ($connectionData->charSet) {
            $this->connection->set_charset($connectionData->charSet);
        }

        $pcs = implode(";", $connectionData->postConnectStatementList);
        if ($pcs) {
            echo $pcs;
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
     * @param string $errorNumber
     * @param int $error
     * @param string $sql
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
                throw new CannotBeNullException($error, $errorNumber, $sql);
            case 1050:
                throw new TableAlreadyExistsException($error, $errorNumber, $sql);
            case 1062:
                throw new UniqueConstraintViolationException($error, $errorNumber, $sql);
            case 1146:
                throw new TableDoesNotExistException($error, $errorNumber, $sql);
            case 1451:
                throw new ForeignKeyConstraintFailedException($error, $errorNumber, $sql);
            default:
                throw new SQLException($error, $errorNumber, $sql);
        }
    }

    /**
     * @return CreateStatementFactory
     */
    public function getCreateStatementFactory()
    {

        return new MySQLCreateStatementFactory();
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
            var_dump($result->getNext());
        }
        $result->close();

        $r =($sequence) ? $sequence : 1;

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
    public function getEntitySourceList($databaseName = null, $targetNamespace = null, $targetPath = null)
    {

        $databaseName = ($databaseName) ? $databaseName : $this->getDatabase();
        $databaseMetaData = new DatabaseMetaData($this);
        return $databaseMetaData->getEntitySourceList($databaseName, $targetNamespace, $targetPath);
    }

    /**
     * @return ColumnMigrator
     */
    public function getColumnMigrator()
    {
        return new MysqlColumnMigrator();
    }
}

