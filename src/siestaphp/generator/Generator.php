<?php

namespace siestaphp\generator;

use Codeception\Util\Debug;
use Psr\Log\LoggerInterface;
use siestaphp\Config;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\ConnectionFactory;
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
     * @var GeneratorConfig
     */
    protected $generatorConfig;


    /**
     * @param LoggerInterface $logger
     * @param GeneratorConfig $config
     */
    public function __construct(LoggerInterface $logger, $config = null)
    {
        $this->generatorConfig = ($config) ? $config : Config::getInstance()->getGeneratorConfig();

        $this->logger = $logger;

        $this->validationLogger = new ValidationLogger($logger);

        $this->dataModelContainer = new DataModelContainer($this->validationLogger);

        $this->directoryScanner = new DirectoryScanner($logger);

        $this->transformerList = [new EntityTransformer(), new EntityManagerTransformer()];
    }


    /**
     * scans the given base dir for entity files and generates them
     *
     * @return void
     */
    public function generate()
    {

        $entitySourceList = $this->directoryScanner->scan($this->generatorConfig->getBaseDir(), $this->generatorConfig->getEntityFileSuffix());

        $this->dataModelContainer->addEntitySourceList($entitySourceList);

        $this->generateDataModelContainer();

    }

    /**
     * generates artifacts for a single file
     *
     * @param string $fileName
     *
     * @return void
     */
    public function generateFile($fileName)
    {

        $xmlReader = new XMLReader(new File($fileName));

        $this->dataModelContainer->addEntitySourceList($xmlReader->getEntitySourceList());

        $this->generateDataModelContainer();
    }

    /**
     * generates all artifacts for current datamodel container
     * @return void
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

    /**
     * @return void
     */
    private function handleDatabaseMigration()
    {
        $connection = ConnectionFactory::getConnection($this->generatorConfig->getConnectionName());
        $connection->disableForeignKeyChecks();

        $this->migrator = new Migrator($this->dataModelContainer, $this->generatorConfig, $this->logger);
        $this->migrator->migrate($this->generatorConfig);
        $connection->enableForeignKeyChecks();

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
            $transformer->transform($ets, $this->generatorConfig->getBaseDir());
        }
    }

}