<?php

declare(strict_types=1);

namespace Siesta\Migration;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Siesta\Database\Connection;
use Siesta\Database\Exception\SQLException;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Logger\NullLogger;
use Siesta\Model\DataModel;
use Siesta\Util\File;

/**
 * @author Gregor Müller
 */
class Migrator implements LoggerAwareInterface
{

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var DatabaseMetaData
     */
    protected DatabaseMetaData $databaseMetaData;

    /**
     * @var DatabaseMigrator
     */
    protected DatabaseMigrator $databaseMigrator;

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;


    /**
     * Migrator constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
    }


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }


    /**
     * @param DataModel $dataModel
     * @param Connection $connection
     */
    protected function setup(DataModel $dataModel, Connection $connection): void
    {
        $this->connection = $connection;

        $this->dataModel = $dataModel;
        $this->databaseMetaData = $connection->getDatabaseMetaData();

        $this->databaseMigrator = new DatabaseMigrator($dataModel, $this->connection);
    }


    /**
     * @param DataModel $dataModel
     * @param Connection $connection
     * @param bool $dropUnusedTables
     */
    public function migrateDirect(DataModel $dataModel, Connection $connection, bool $dropUnusedTables = true): void
    {
        $this->setup($dataModel, $connection);
        $dropping = $dropUnusedTables ? " dropping unused tables " : " not dropping unused tables";
        $database = $this->connection->getDatabase();
        $this->logger->info("Direct migration of database " . $database . $dropping);

        $this->databaseMigrator->createAlterStatementList($dropUnusedTables);
        $this->connection->disableForeignKeyChecks();

        try {
            foreach ($this->databaseMigrator->getAlterStatementList() as $statement) {
                $this->logger->warning($statement);
                $this->connection->query($statement);
            }

            foreach ($this->databaseMigrator->getAlterStoredProcedureStatementList() as $statement) {
                $this->logger->warning($statement);
                $this->connection->query($statement);
            }
        } catch (SQLException $e) {
            $this->logger->error("SQL Exception : " . $e->getMessage() . " (" . $e->getCode() . ")");
            $this->logger->error("Query : " . $e->getSQL());
        }
        $this->connection->enableForeignKeyChecks();
    }


    /**
     * @param DataModel $dataModel
     * @param Connection $connection
     * @param File $targetFile
     * @param bool $dropUnusedTables
     */
    public function migrateToSQLFile(DataModel $dataModel, Connection $connection, File $targetFile, bool $dropUnusedTables = true): void
    {
        $targetFile->createDirForFile();
        $this->setup($dataModel, $connection);

        $dropping = $dropUnusedTables ? " dropping unused tables " : " not dropping unused tables";
        $database = $this->connection->getDatabase();
        $this->logger->info("Storing alter statements for database " . $database . $dropping);
        $this->logger->info("Used file " . $targetFile->getAbsoluteFileName());

        $this->databaseMigrator->createAlterStatementList($dropUnusedTables);

        $alterStatementList = $this->databaseMigrator->getAlterStatementList();
        $statementList = $this->databaseMigrator->getAlterStoredProcedureStatementList();

        $statementList = array_merge($alterStatementList, $statementList);

        $targetFile->putContents(implode(";" . PHP_EOL, $statementList));
    }

}