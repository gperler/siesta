<?php

declare(strict_types=1);

namespace Siesta\Main;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Siesta\Config\GenericConfigLoader;
use Siesta\Contract\DataModelUpdater;
use Siesta\Database\Connection;
use Siesta\Database\ConnectionFactory;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Generator\MainGenerator;
use Siesta\Migration\Migrator;
use Siesta\Model\DataModel;
use Siesta\Model\ValidationLogger;
use Siesta\NamingStrategy\NamingStrategy;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\Util\File;
use Siesta\Validator\Validator;
use Siesta\XML\XMLDirectoryScanner;

/**
 * @author Gregor Müller
 */
class Siesta implements LoggerAwareInterface
{
    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $validationLogger;

    /**
     * @var XMLDirectoryScanner
     */
    protected XMLDirectoryScanner $directoryScanner;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var GenericConfigLoader
     */
    protected GenericConfigLoader $genericConfigLoader;

    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @var MainGenerator
     */
    protected MainGenerator $mainGenerator;

    /**
     * @var Migrator
     */
    protected Migrator $migrator;

    /**
     * @var Connection|null
     */
    protected ?Connection $connection;

    /**
     * @var DataModelUpdater
     */
    protected DataModelUpdater $dataModelUpdater;

    /**
     * time when the last time was generated
     *
     * @var int|null
     */
    protected ?int $lastGenerationTime;


    /**
     * Siesta constructor.
     */
    public function __construct()
    {
        $this->connection = null;

        $this->validationLogger = new ValidationLogger();

        $this->directoryScanner = new XMLDirectoryScanner($this->validationLogger);

        $this->dataModel = new DataModel();

        $this->genericConfigLoader = new GenericConfigLoader();

        $this->validator = new Validator();

        $this->mainGenerator = new MainGenerator();

        $this->migrator = new Migrator();

        $this->dataModelUpdater = new NoUpdate();

        $this->lastGenerationTime = null;
    }


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->validationLogger->setLogger($logger);
        $this->migrator->setLogger($logger);
    }


    /**
     * @param File $file
     */
    public function addFile(File $file): void
    {
        $this->directoryScanner->addFile($file);
    }


    /**
     * @param string $extension
     * @param string|null $baseDir
     */
    public function addFileType(string $extension, string $baseDir = null): void
    {
        $this->directoryScanner->addFileExtension($extension, $baseDir, $this->lastGenerationTime);
    }


    /**
     * @param File $file
     */
    public function setGenericGeneratorConfig(File $file): void
    {
        $this->genericConfigLoader->setConfigFile($file);
    }


    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }
        return ConnectionFactory::getConnection();
    }


    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }


    /**
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    protected function setup(): void
    {
        $genericConfig = $this->genericConfigLoader->loadAndValidate($this->validationLogger);
        $this->checkValidationError();
        $this->validator->setup($genericConfig);
        $this->mainGenerator->setup($genericConfig);
    }


    /**
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    protected function prepareModel(): void
    {
        $xmlEntityList = $this->directoryScanner->getXmlEntityList();
        $this->dataModel->addXMLEntityList($xmlEntityList);

        $this->dataModelUpdater->preUpdateModel($this->dataModel);
        $this->dataModel->update();
        $this->dataModelUpdater->postUpdateModel($this->dataModel);

        $this->validator->validateDataModel($this->dataModel, $this->validationLogger);
        $this->checkValidationError();
    }


    /**
     * @param string $baseDir
     *
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    protected function generateClasses(string $baseDir): void
    {
        $this->setup();
        $this->prepareModel();
        $this->mainGenerator->generate($this->dataModel, $baseDir);
    }


    /**
     * @param string $baseDir
     * @param bool $dropUnusedTables
     *
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    public function migrateDirect(string $baseDir, bool $dropUnusedTables = true): void
    {
        $this->generateClasses($baseDir);
        $this->migrator->migrateDirect($this->dataModel, $this->getConnection(), $dropUnusedTables);
    }


    /**
     * @param string $baseDir
     * @param File $targetFile
     * @param bool $dropUnusedTables
     *
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    public function migrateToFile(string $baseDir, File $targetFile, bool $dropUnusedTables = true): void
    {
        $this->generateClasses($baseDir);
        $this->migrator->migrateToSQLFile($this->dataModel, $this->getConnection(), $targetFile, $dropUnusedTables);
    }


    /**
     * @throws InvalidConfigurationException
     */
    protected function checkValidationError(): void
    {
        if (!$this->validationLogger->hasError()) {
            return;
        }
        throw new InvalidConfigurationException();
    }


    /**
     * @param NamingStrategy $strategy
     */
    public function setTableNamingStrategy(NamingStrategy $strategy): void
    {
        NamingStrategyRegistry::setTableNamingStrategy($strategy);
    }


    /**
     * @param NamingStrategy $strategy
     */
    public function setColumnNamingStrategy(NamingStrategy $strategy): void
    {
        NamingStrategyRegistry::setColumnNamingStrategy($strategy);
    }


    /**
     * @param string|null $fileName
     */
    public function setGenericConfigFileName(string $fileName = null): void
    {
        $this->genericConfigLoader->setConfigFileName($fileName);
    }


    /**
     * @param DataModelUpdater $dataModelUpdater
     */
    public function setDataModelUpdater(DataModelUpdater $dataModelUpdater): void
    {
        $this->dataModelUpdater = $dataModelUpdater;
    }


    /**
     * @param int|null $lastGenerationTime
     */
    public function setLastGenerationTime(?int $lastGenerationTime): void
    {
        $this->lastGenerationTime = $lastGenerationTime;
    }


}