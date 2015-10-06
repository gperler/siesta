<?php

namespace siestaphp\generator;


use Codeception\Util\Debug;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntityTransformerSource;
use siestaphp\runtime\ServiceLocator;
use siestaphp\util\File;
use siestaphp\xmlreader\DirectoryScanner;
use siestaphp\xmlreader\XMLReader;

/**
 * Class Generator
 * @package siestaphp\generator
 */
class Generator
{


    /**
     * @var GeneratorLog
     */
    protected $generatorLog;

    /**
     * @var DataModelContainer
     */
    protected $dataModelContainer;


    /**
     * @var DirectoryScanner
     */
    protected $directoryScanner;

    /**
     * @var Transformer[]
     */
    protected $transformerList;


    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var number
     */
    protected $startTime;

    /**
     * initializes the generator
     */
    public function __construct()
    {

        $this->generatorLog = new GeneratorConsoleLog();

        $this->dataModelContainer = new DataModelContainer($this->generatorLog);

        $this->directoryScanner = new DirectoryScanner($this->dataModelContainer, $this->generatorLog);

        $this->transformerList = array(
            new EntityTransformer()
        );
    }


    /**
     * scans the given base dir for entity files and generates them
     *
     * @param string $baseDir
     */
    public function generate($baseDir)
    {
        $this->startTime = -time();

        $this->baseDir = $baseDir;

        $entitySourceList = $this->directoryScanner->scan($this->generatorLog, $this->baseDir);

        $this->dataModelContainer->addEntitySourceList($entitySourceList);

        $this->generateDataModelContainer();

        $this->generatorLog->info($this->startTime + time() . "ms");
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

        if ($this->generatorLog->hasErrors()) {
            return;
        }

        $this->applyTransformerToEntityList();

        $this->setupTablesAndStoredProcedures();
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
     * @param EntityTransformerSource $ets
     */
    private function applyTransformerToEntity(EntityTransformerSource $ets)
    {
        foreach ($this->transformerList as $transformer) {
            $transformer->transform($ets, $this->baseDir);
        }
   }

    /**
     * creates the tables and stored procedures needed
     */
    private function setupTablesAndStoredProcedures()
    {
        $driver = ServiceLocator::getDriver();
        $driver->install();

        $driver->disableForeignKeyChecks();

        $tableBuilder = $driver->getTableBuilder();
        foreach ($this->dataModelContainer->getEntityList() as $ets) {
            $tableBuilder->setupTables($ets);
            $tableBuilder->setupStoredProcedures($ets);
        }

        $driver->enableForeignKeyChecks();
    }


}