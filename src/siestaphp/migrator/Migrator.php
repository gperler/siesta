<?php

namespace siestaphp\migrator;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\driver\Connection;
use siestaphp\driver\exceptions\SQLException;
use siestaphp\generator\GeneratorLog;

/**
 * Class Migrator
 * @package siestaphp\migrator
 */
class Migrator
{
    const DIRECT_EXECUTION = 1;
    const CREATE_SQL_FILE = 2;
    const CREATE_PHP_FILE = 3;

    /**
     * @var DatabaseMigrator
     */
    protected $databaseMigrator;

    /**
     * @var DataModelContainer
     */
    protected $dataModelContainer;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var int
     */
    protected $executionMode;


    /**
     * @param DataModelContainer $dataModelContainer
     * @param Connection $connection
     * @param GeneratorLog $logger
     */
    public function __construct(DataModelContainer $dataModelContainer, Connection $connection, GeneratorLog $logger) {
        $this->dataModelContainer = $dataModelContainer;
        $this->connection = $connection;
        $this->executionMode = self::DIRECT_EXECUTION;
        $this->databaseMigrator = new DatabaseMigrator($dataModelContainer, $connection);
    }

    /**
     * @param $executionMode
     */
    public function setExecutionMode($executionMode) {
        if ($executionMode < self::DIRECT_EXECUTION or $executionMode > self::CREATE_PHP_FILE) {
            return;
        }
        $this->executionMode = $executionMode;
    }

    /**
     * @param bool $dropUnUsedTables
     */
    public function migrate($dropUnUsedTables = false) {
        $statementList = $this->databaseMigrator->createAlterStatementList($dropUnUsedTables);

        $this->migrateDirect($statementList);
    }

    /**
     * @param string $statementList
     */
    private function migrateDirect($statementList) {
        try {
            $this->connection->multiQuery(implode(";", $statementList));
        } catch (SQLException $e) {

        }
    }


}