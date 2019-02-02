<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL;

use Siesta\Database\Connection;
use Siesta\Database\ConnectionData;
use Siesta\Database\CreateStatementFactory;
use Siesta\Database\Exception\CannotBeNullException;
use Siesta\Database\Exception\ConnectException;
use Siesta\Database\Exception\ForeignKeyConstraintFailedException;
use Siesta\Database\Exception\SQLException;
use Siesta\Database\Exception\TableAlreadyExistsException;
use Siesta\Database\Exception\TableDoesNotExistException;
use Siesta\Database\Exception\UniqueConstraintViolationException;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Database\ResultSet;
use Siesta\Database\StoredProcedureDefinition;
use Siesta\Database\StoredProcedureFactory;
use Siesta\Driver\MySQL\MetaData\MySQLDatabase;

/**
 * @author Gregor Müller
 */
class MySQLConnection implements Connection
{
    const NAME = "mysql";

    /**
     * @var \mysqli
     */
    private $connection;

    /**
     * @var string
     */
    private $database;

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
     *
     */
    public function getDatabase(): string
    {
        return $this->database;
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
            $this->connection = @new \mysqli ($connectionData->host, $connectionData->user, $connectionData->password, "", $connectionData->port);
        }

        // check for errors
        if ($this->connection->connect_errno) {
            throw new ConnectException ($connectionData, "Can't connect to " . $connectionData->host . " : " . $this->connection->connect_error, $this->connection->connect_errno);
        }

        // switch character set
        if ($connectionData->charSet) {
            $this->connection->set_charset($connectionData->charSet);
        }

        foreach ($connectionData->postConnectStatementList as $statement) {
            $this->execute($statement);
        }
    }

    /**
     * @param string $name
     */
    public function useDatabase(string $name)
    {
        $this->database = $name;
        $this->connection->query("USE " . $name);
    }

    /**
     * @param string $query
     *
     * @throws CannotBeNullException
     * @throws ForeignKeyConstraintFailedException
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     * @return ResultSet
     */
    public function query(string $query): ResultSet
    {
        $result = $this->connection->query($query);

        if ($result === false) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }
        if ($result === true) {
            return new MySQLEmptyResult();
        }

        return new MySQLSimpleResultSet($result);
    }

    /**
     * @param string $query
     *
     * @return ResultSet
     * @throws CannotBeNullException
     * @throws ForeignKeyConstraintFailedException
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     */
    public function multiQuery(string $query): ResultSet
    {
        $result = $this->connection->multi_query($query);
        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }
        return new MySQLMultiQueryResultSet($this->connection);
    }

    /**
     * execute an sql command and free resulSet.
     *
     * @param string $query
     *
     * @throws CannotBeNullException
     * @throws ForeignKeyConstraintFailedException
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     */
    public function execute(string $query)
    {
        $result = $this->connection->multi_query($query);
        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }
        while ($this->connection->more_results()) {
            $this->connection->next_result();
            $resultSet = $this->connection->use_result();
        }
    }

    /**
     * @param $query
     *
     * @throws CannotBeNullException
     * @throws ForeignKeyConstraintFailedException
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     * @return ResultSet
     */
    public function executeStoredProcedure(string $query): ResultSet
    {
        $result = $this->connection->multi_query($query);

        if (!$result) {
            $this->handleQueryError($this->connection->errno, $this->connection->error, $query);
        }

        return new MySQLMultiQueryResultSet($this->connection);
    }

    /**
     * @param int $errorNumber
     * @param string $error
     * @param string $sql
     *
     * @throws CannotBeNullException
     * @throws ForeignKeyConstraintFailedException
     * @throws SQLException
     * @throws TableAlreadyExistsException
     * @throws TableDoesNotExistException
     * @throws UniqueConstraintViolationException
     */
    private function handleQueryError(int $errorNumber, string $error, string $sql)
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
     * @param string $value
     *
     * @return string
     */
    public function escape(string $value)
    {
        return $this->connection->real_escape_string($value);
    }

    /**
     * @param string $technicalName
     *
     * @return int
     */
    public function getSequence(string $technicalName)
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
        $this->query("set foreign_key_checks=1");
    }

    public function disableForeignKeyChecks()
    {
        $this->query("set foreign_key_checks=0");
    }

    /**
     *
     */
    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    /**
     * @param string|null $databaseName
     *
     * @return DatabaseMetaData
     */
    public function getDatabaseMetaData(string $databaseName = null): DatabaseMetaData
    {
        return new MySQLDatabase($this, $databaseName);
    }

    /**
     * @return MigrationStatementFactory
     */
    public function getMigrationStatementFactory(): MigrationStatementFactory
    {
        return new MySQLMigrationStatementFactory();
    }

    /**
     * @return CreateStatementFactory
     */
    public function getCreateStatementFactory(): CreateStatementFactory
    {
        return new MySQLCreateStatementFactory();
    }

    /**
     * @return StoredProcedureFactory
     */
    public function getStoredProcedureFactory(): StoredProcedureFactory
    {
        return new MySQLStoredProcedureFactory();
    }
}

