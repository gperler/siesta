<?php

namespace siestaphp\generator;

use Psr\Log\LoggerInterface;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\SQLException;
use siestaphp\migrator\Migrator;
use siestaphp\util\File;
use siestaphp\xmlreader\DirectoryScanner;
use siestaphp\xmlreader\XMLReader;

/**
 * Class Generator
 * @package siestaphp\generator
 */
class Generator
{

    const ERROR_SQL_EXCEPTION = 1100;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ValidationLogger
     */
    protected $validationLogger;

    /**
     * @var DataModelContainer
     */
    protected $dataModelContainer;

    /**
     * @var DirectoryScanner
     */
    protected $directoryScanner;

    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var Transformer[]
     */
    protected $transformerList;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $entityFileSuffix;

    /**
     * @var string
     */
    protected $conncectionName;

    /**
     * @var bool
     */
    protected $dropUnusedTables;

    /**
     * @var int
     */
    protected $migrationMode;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;

        $this->validationLogger = new ValidationLogger($logger);

        $this->dataModelContainer = new DataModelContainer($this->validationLogger);

        $this->directoryScanner = new DirectoryScanner();

        $this->transformerList = array(new EntityTransformer());

        $this->dropUnusedTables = false;

        $this->conncectionName = null;

        $this->entityFileSuffix = DirectoryScanner::DEFAULT_SUFFIX;

    }

    /**
     * @param string $suffix
     */
    public function setEntityFileSuffix($suffix)
    {
        $this->entityFileSuffix = $suffix;
    }

    /**
     * @param bool $drop
     */
    public function dropUnusedTables($drop)
    {
        $this->dropUnusedTables = $drop;
    }

    /**
     * @param string $connectionName
     */
    public function setConnectionName($connectionName)
    {
        $this->conncectionName = $connectionName;
    }

    /**
     * scans the given base dir for entity files and generates them
     *
     * @param string $baseDir
     */
    public function generate($baseDir)
    {
        $time = -microtime(true);

        $this->baseDir = $baseDir;

        $entitySourceList = $this->directoryScanner->scan($this->validationLogger, $this->baseDir, $this->entityFileSuffix);

        $this->dataModelContainer->addEntitySourceList($entitySourceList);

        $this->generateDataModelContainer();

        $time = (microtime(true) + $time) / 1000;
        $this->validationLogger->info(sprintf("%0.3fms", $time));

    }

    /**
     * generates artifacts for a single file
     *
     * @param string $baseDir
     * @param string $fileName
     */
    public function generateFile($baseDir, $fileName)
    {
        $this->baseDir = $baseDir;

        $xmlReader = new XMLReader(new File($fileName));

        $this->dataModelContainer->addEntitySourceList($xmlReader->getEntitySourceList());

        $this->generateDataModelContainer();
    }

    /**
     * generates all artifacts for current datamodel container
     */
    private function generateDataModelContainer()
    {
        $this->dataModelContainer->updateModel();

        $this->dataModelContainer->validate();

        if ($this->validationLogger->hasErrors()) {
            return;
        }

        $this->applyTransformerToEntityList();

        $this->handleDatabaseMigration();
    }

    private function handleDatabaseMigration()
    {
        try {
            $connection = ConnectionFactory::getConnection($this->conncectionName);
            $connection->install();
            $connection->disableForeignKeyChecks();

            $this->migrator = new Migrator($this->dataModelContainer, $connection, $this->logger);
            $this->migrator->migrate();
            $connection->enableForeignKeyChecks();
        } catch (SQLException $s) {
            $this->validationLogger->error("SQL Exception " . $s->getMessage(), self::ERROR_SQL_EXCEPTION);
        }

    }

    /**
     * applies the transformers to the all entities
     */
    private function applyTransformerToEntityList()
    {
        $entityTransformerSourceList = $this->dataModelContainer->getEntityList();

        foreach ($entityTransformerSourceList as $ets) {
            $this->applyTransformerToEntity($ets);
        }

    }

    /**
     * Applies all registered transformers to the entitysource
     *
     * @param EntityGeneratorSource $ets
     */
    private function applyTransformerToEntity(EntityGeneratorSource $ets)
    {
        foreach ($this->transformerList as $transformer) {
            $transformer->transform($ets, $this->baseDir);
        }
    }

}