<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;
use Siesta\Database\CreateStatementFactory;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Model\StoredProcedure;

/**
 * @author Gregor Müller
 */
class MySQLDatabase implements DatabaseMetaData
{

    const SQL_GET_SP_LIST = "SELECT ROUTINE_NAME FROM information_schema.ROUTINES WHERE ROUTINE_TYPE = 'PROCEDURE' AND ROUTINE_SCHEMA = '%s';";

    const SQL_GET_TABLE_LIST = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='%s';";

    const ROUTINE_NAME = "ROUTINE_NAME";

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var MySQLTable[]
     */
    protected $tableList;

    /**
     * MySQLDatabase constructor.
     *
     * @param Connection $connection
     * @param string|null $databaseName
     */
    public function __construct(Connection $connection, string $databaseName = null)
    {
        $this->connection = $connection;
        $this->tableList = [];
        if ($databaseName !== null) {
            $this->connection->useDatabase($databaseName);
        }
        $this->readMySQLTable();
    }

    /**
     *
     */
    public function refresh()
    {
        $this->tableList = [];
        $this->readMySQLTable();
    }

    /**
     * @return array
     */
    public function getTableList(): array
    {
        return $this->tableList;
    }

    /**
     * @param string $tableName
     *
     * @return TableMetaData|null
     */
    public function getTableByName(string $tableName)
    {
        foreach ($this->tableList as $table) {
            if ($table->getName() === $tableName) {
                return $table;
            }
        }
        return null;
    }

    /**
     *
     */
    protected function readMySQLTable()
    {
        $tableDTOList = $this->getTableDTO();

        foreach ($tableDTOList as $tableDTO) {
            // do not care about sequencer table
            if ($tableDTO->name === CreateStatementFactory::SEQUENCER_TABLE_NAME) {
                continue;
            }
            $this->tableList[] = new MySQLTable($this->connection, $tableDTO);
        }
    }

    /**
     * @return TableDTO[]
     */
    protected function getTableDTO(): array
    {
        $tableDTOList = [];

        $sql = sprintf(self::SQL_GET_TABLE_LIST, $this->connection->getDatabase());
        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $tableDTOList[] = new TableDTO($resultSet);
        }
        $resultSet->close();

        return $tableDTOList;
    }

    /**
     * @return StoredProcedure[]
     */
    public function getStoredProcedureList(): array
    {
        $spList = [];
        $sql = sprintf(self::SQL_GET_SP_LIST, $this->connection->getDatabase());
        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {
            $procedureName = $resultSet->getStringValue(self::ROUTINE_NAME);
            $spList[] = new MySQLStoredProcedure($procedureName);
        }
        foreach ($spList as $sp) {
            $sp->load($this->connection);
        }

        return $spList;
    }

}