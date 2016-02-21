<?php

namespace siestaphp\migrator;

use Psr\Log\LoggerInterface;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\SQLException;
use siestaphp\generator\GeneratorConfig;
use siestaphp\util\File;

/**
 * Class Migrator
 * @package siestaphp\migrator
 */
class Migrator
{

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
     * @var GeneratorConfig
     */
    protected $config;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param DataModelContainer $dataModelContainer
     * @param GeneratorConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(DataModelContainer $dataModelContainer, GeneratorConfig $config, LoggerInterface $logger)
    {
        $this->dataModelContainer = $dataModelContainer;
        $this->config = $config;
        $this->connection = ConnectionFactory::getConnection($config->getConnectionName());
        $this->databaseMigrator = new DatabaseMigrator($dataModelContainer, $this->connection);
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function migrate()
    {
        $this->databaseMigrator->createAlterStatementList(null, null, $this->config->isDropUnusedTables());

        switch ($this->config->getMigrationMethod()) {
            case GeneratorConfig::MIGRATION_DIRECT_EXECUTION:
                $this->migrateDirect();
                break;
            case GeneratorConfig::MIGRATOR_CREATE_PHP_FILE:
                break;
            case GeneratorConfig::MIGRATOR_CREATE_SQL_FILE:
                $this->migrateSQLFile();
                break;
        }
    }

    /**
     */
    private function migrateDirect()
    {
        try {
            $alterStatementList = $this->databaseMigrator->getAlterStatementList();

            $datbase = $this->connection->getDatabase();
            $this->logger->notice("Direct migration of database " . $datbase);

            $this->connection->disableForeignKeyChecks();
            foreach ($alterStatementList as $statement) {
                $this->logger->warning("Altering " . $statement);
                $this->connection->query($statement);
            }
            $this->connection->enableForeignKeyChecks();

            $statementList = $this->databaseMigrator->getStatementList();
            foreach ($statementList as $statement) {
                $this->logger->info("Executing " . $statement);
                $this->connection->query($statement);
            }

        } catch (SQLException $e) {
            $this->logger->error("SQL Exception : " . $e->getMessage() . " (" . $e->getCode() . ")");
            $this->logger->error("Query : " . $e->getSQL());
        }
    }

    /**
     */
    private function migrateSQLFile()
    {
        $targetDir = new File($this->config->getMigrationTargetPath());
        $targetDir->createDir();

        $targetFile = new File($targetDir . "/migration.sql");

        $alterStatementList = $this->databaseMigrator->getAlterStatementList();
        $statementList = $this->databaseMigrator->getStatementList();

        $statementList = array_merge($alterStatementList, $statementList);

        $targetFile->putContents(implode(";" . PHP_EOL, $statementList));
    }

}