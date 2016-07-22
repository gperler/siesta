<?php
declare(strict_types = 1);

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
    protected $dataModel;

    /**
     * @var DatabaseMetaData
     */
    protected $databaseMetaData;

    /**
     * @var DatabaseMigrator
     */
    protected $databaseMigrator;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param DataModel $dataModel
     * @param Connection $connection
     */
    protected function setup(DataModel $dataModel, Connection $connection)
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
    public function migrateDirect(DataModel $dataModel, Connection $connection, bool $dropUnusedTables = true)
    {
        $this->setup($dataModel, $connection);
        $dropping = $dropUnusedTables ? " dropping unused tables " : " not dropping unused tables";
        $datbase = $this->connection->getDatabase();
        $this->logger->info("Direct migration of database " . $datbase . $dropping);

        $this->databaseMigrator->createAlterStatementList($dropUnusedTables);

        $this->connection->disableForeignKeyChecks();

        try {

            foreach ($this->databaseMigrator->getDropStoredProcedureStatementList() as $statement) {
                $this->logger->debug("Executing " . $statement);
                $this->connection->execute($statement);
            }

            foreach ($this->databaseMigrator->getAlterStatementList() as $statement) {
                $this->logger->warning("Altering " . $statement);
                $this->connection->query($statement);
            }

            foreach ($this->databaseMigrator->getCreateStoredProcedureStatementList() as $statement) {
                $this->logger->debug("Executing " . $statement);
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
    public function migrateToSQLFile(DataModel $dataModel, Connection $connection, File $targetFile, bool $dropUnusedTables = true)
    {
        $targetFile->createDirForFile();
        $this->setup($dataModel, $connection);

        $dropping = $dropUnusedTables ? " dropping unused tables " : " not dropping unused tables";
        $datbase = $this->connection->getDatabase();
        $this->logger->info("Storing alter statements for database " . $datbase . $dropping);
        $this->logger->info("Used file " . $targetFile->getAbsoluteFileName());

        $this->databaseMigrator->createAlterStatementList($dropUnusedTables);

        $dropProcedureStatementList = $this->databaseMigrator->getDropStoredProcedureStatementList();
        $alterStatementList = $this->databaseMigrator->getAlterStatementList();
        $statementList = $this->databaseMigrator->getCreateStoredProcedureStatementList();

        $statementList = array_merge($dropProcedureStatementList, $alterStatementList, $statementList);

        $targetFile->putContents(implode(";" . PHP_EOL, $statementList));
    }

}