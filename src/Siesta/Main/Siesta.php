<?php

declare(strict_types = 1);

namespace Siesta\Main;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Siesta\Config\GenericConfigLoader;
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
    protected $validationLogger;

    /**
     * @var XMLDirectoryScanner
     */
    protected $directoryScanner;

    /**
     * @var DataModel
     */
    protected $dataModel;

    /**
     * @var GenericConfigLoader
     */
    protected $genericConfigLoader;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var MainGenerator
     */
    protected $mainGenerator;

    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * Siesta constructor.
     */
    public function __construct()
    {
        $this->validationLogger = new ValidationLogger();

        $this->directoryScanner = new XMLDirectoryScanner($this->validationLogger);

        $this->dataModel = new DataModel();

        $this->genericConfigLoader = new GenericConfigLoader();

        $this->validator = new Validator();

        $this->mainGenerator = new MainGenerator();

        $this->migrator = new Migrator();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->validationLogger->setLogger($logger);
        $this->migrator->setLogger($logger);
    }

    /**
     * @param File $file
     */
    public function addFile(File $file)
    {
        $this->directoryScanner->addFile($file);
    }

    /**
     * @param string $extension
     * @param string|null $baseDir
     */
    public function addFileType(string $extension, string $baseDir = null)
    {
        $this->directoryScanner->addFileExtension($extension, $baseDir);
    }

    /**
     * @param File $file
     */
    public function setGenericGeneratorConfig(File $file)
    {
        $this->genericConfigLoader->setConfigFile($file);
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        if ($this->connection !== null) {
            return $this->connection;
        }
        return ConnectionFactory::getConnection();
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws InvalidConfigurationException
     */
    protected function setup()
    {
        $genericConfig = $this->genericConfigLoader->loadAndValidate($this->validationLogger);
        $this->checkValidationError();
        $this->validator->setup($genericConfig);
        $this->mainGenerator->setup($genericConfig);
    }

    /**
     * @throws InvalidConfigurationException
     */
    protected function prepareModel()
    {
        $xmlEntityList = $this->directoryScanner->getXmlEntityList();
        $this->dataModel->addXMLEntityList($xmlEntityList);
        $this->dataModel->update();
        $this->validator->validateDataModel($this->dataModel, $this->validationLogger);
        $this->checkValidationError();
    }

    /**
     * @param string $baseDir
     * @param bool $dropUnusedTables
     *
     * @throws InvalidConfigurationException
     */
    protected function generateClasses(string $baseDir, bool $dropUnusedTables = true)
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
     */
    public function migrateDirect(string $baseDir, bool $dropUnusedTables = true)
    {
        $this->generateClasses($baseDir, $dropUnusedTables);
        $this->migrator->migrateDirect($this->dataModel, $this->getConnection(), $dropUnusedTables);

    }

    /**
     * @param string $baseDir
     * @param File $targetFile
     * @param bool $dropUnusedTables
     *
     * @throws InvalidConfigurationException
     */
    public function migrateToFile(string $baseDir, File $targetFile, bool $dropUnusedTables = true)
    {
        $this->generateClasses($baseDir, $dropUnusedTables);
        $this->migrator->migrateToSQLFile($this->dataModel, $this->getConnection(), $targetFile, $dropUnusedTables);
    }

    /**
     * @throws InvalidConfigurationException
     */
    protected function checkValidationError()
    {
        if (!$this->validationLogger->hasError()) {
            return;
        }
        throw new InvalidConfigurationException();
    }

    /**
     * @param NamingStrategy $strategy
     */
    public function setTableNamingStrategy(NamingStrategy $strategy)
    {
        NamingStrategyRegistry::setTableNamingStrategy($strategy);
    }

    /**
     * @param NamingStrategy $strategy
     */
    public function setColumnNamingStrategy(NamingStrategy $strategy)
    {
        NamingStrategyRegistry::setColumnNamingStrategy($strategy);
    }

    /**
     * @param string|null $fileName
     */
    public function setGenericConfigFileName(string $fileName = null)
    {
        $this->genericConfigLoader->setConfigFileName($fileName);
    }
}