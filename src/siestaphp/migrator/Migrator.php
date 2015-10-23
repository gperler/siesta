<?php

namespace siestaphp\migrator;

use Psr\Log\LoggerInterface;
use siestaphp\Config;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\SQLException;
use siestaphp\generator\GeneratorConfig;
use siestaphp\util\File;
use siestaphp\util\StringUtil;

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
        $statementList = $this->databaseMigrator->createAlterStatementList(null,null, $this->config->isDropUnusedTables());

        $alterStatementList = $this->databaseMigrator->getAlterStatementList();
        $this->logger->info(implode(PHP_EOL, $alterStatementList));

        switch($this->config->getMigrationMethod()) {
            case GeneratorConfig::MIGRATION_DIRECT_EXECUTION:
                $this->migrateDirect($statementList);
                break;
            case GeneratorConfig::MIGRATOR_CREATE_PHP_FILE:
                break;
            case GeneratorConfig::MIGRATOR_CREATE_SQL_FILE:
                $this->migrateSQLFile($statementList);
                break;
        }
    }

    /**
     * @param string[] $statementList
     */
    private function migrateDirect(array $statementList)
    {
        try {
            $datbase = $this->connection->getDatabase();
            $this->logger->info("Direct migration of database " . $datbase);

            $this->connection->disableForeignKeyChecks();
            foreach ($statementList as $statement) {
                $this->logger->debug("Executing " . $statement);
                $this->connection->query($statement);
            }
            $this->connection->enableForeignKeyChecks();

        } catch (SQLException $e) {
            $this->logger->error("SQL Exception : " . $e->getMessage() . " (" . $e->getCode() . ")");
            $this->logger->error("Query : " . $e->getSQL());
        }
    }

    /**
     * @param array $statementList
     */
    private function migrateSQLFile(array $statementList) {
        $targetDir = new File($this->config->getMigrationTargetPath());
        $targetDir->createDir();

        $targetFile = new File($targetDir . "/migration.sql");

        $targetFile->putContents(implode(";" . PHP_EOL, $statementList));
    }

}