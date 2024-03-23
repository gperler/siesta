<?php

namespace Siesta\Console;

use ReflectionException;
use Siesta\Config\Config;
use Siesta\Config\MainGeneratorConfig;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Exception\ConnectException;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Logger\SymphonyConsoleLogger;
use Siesta\Main\Siesta;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GreetCommand
 *
 * @package AppBundle\Command
 */
class GeneratorCommand extends Command
{

    const OPTION_CONFIG_FILE = "configFile";

    const OPTION_LAST_GENERATION_TIME = "lastGenerationTime";

    const OPTION_DATA_MODEL_UPDATER = "dataModelUpdater";

    const NO_CONFIG_FILE = "<error>No config file found. Run 'vendor/bin/siesta init' to generate one. </error>";

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var MainGeneratorConfig
     */
    protected $generatorConfig;

    /**
     * @var Siesta
     */
    protected $siesta;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;


    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('gen');
        $this->setDescription('Scans directories for entity files and generates classes and database tables');
        $this->addOption(self::OPTION_CONFIG_FILE, null, InputOption::VALUE_OPTIONAL, "Path to config file to use.");
        $this->addOption(self::OPTION_LAST_GENERATION_TIME, null, InputOption::VALUE_OPTIONAL, "timestamp of the last generation");
        $this->addOption(self::OPTION_DATA_MODEL_UPDATER, null, InputOption::VALUE_OPTIONAL, "data model updater");
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->input = $input;
        $this->siesta = new Siesta();
        try {
            $this->getConfiguration();
            $output->writeln("I'm using config file " . $this->config->getConfigFileName());

            $this->setupSiesta();

            $baseDir = $this->generatorConfig->getBaseDir();
            $dropUnusedTables = $this->generatorConfig->isDropUnusedTables();

            if ($this->generatorConfig->isMigrateDirect()) {
                $this->siesta->migrateDirect($baseDir, $dropUnusedTables);
            }

            if ($this->generatorConfig->isMigrationToFile()) {
                $targetFile = $this->generatorConfig->getMigrationFile();
                $this->siesta->migrateToFile($baseDir, $targetFile, $dropUnusedTables);
            }
            return 0;
        } catch (ConnectException $ce) {
            $this->output->writeln($ce->getMessage());
            $this->output->writeln("Config file used " . Config::getInstance()->getConfigFileName());
            $this->output->writeln("Connection Configuration");
            $this->output->writeln((string)$ce->getConnectionData());
            return 1;

        } catch (InvalidConfigurationException $ic) {
            $this->output->writeln($ic->getMessage());
            $this->output->writeln("Please run 'vendor/bin/siesta init' to generate a config file");
            return 1;
        }
    }


    /**
     * @return void
     * @throws ReflectionException
     */
    protected function getConfiguration(): void
    {
        $configFileName = $this->input->getOption(GeneratorCommand::OPTION_CONFIG_FILE);
        $this->config = Config::getInstance($configFileName);
        if ($this->config === null) {
            $this->output->writeln(self::NO_CONFIG_FILE);
            exit();
        }
        $this->generatorConfig = $this->config->getMainGeneratorConfig();
    }


    /**
     *
     */
    protected function setupSiesta(): void
    {
        $logger = new SymphonyConsoleLogger($this->output);
        $this->siesta->setLogger($logger);

        $lastGenerationTime = $this->input->getOption(self::OPTION_LAST_GENERATION_TIME);
        $this->siesta->setLastGenerationTime($lastGenerationTime);

        $dataModelUpdater = $this->input->getOption(self::OPTION_DATA_MODEL_UPDATER);
        if ($dataModelUpdater !== null) {
            $this->siesta->setDataModelUpdater(new $dataModelUpdater);
        }

        $this->siesta->addFileType($this->generatorConfig->getEntityFileSuffix());
        $this->siesta->setColumnNamingStrategy($this->generatorConfig->getColumnNamingStrategyInstance());

        $connection = ConnectionFactory::getConnection($this->generatorConfig->getConnectionName());
        $this->siesta->setConnection($connection);

        $this->siesta->setGenericConfigFileName($this->generatorConfig->getGenericGeneratorConfiguration());
    }

}