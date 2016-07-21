<?php

namespace Siesta\Console;

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
 * @package AppBundle\Command
 */
class GeneratorCommand extends Command
{

    const OPTION_CONFIG_FILE = "configFile";

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
    protected function configure()
    {
        $this->setName('generate');
        $this->setDescription('Scans directories for entity files and generates classes and database tables');
        $this->addOption(self::OPTION_CONFIG_FILE, null, InputOption::VALUE_OPTIONAL, "Path to config file to use.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $this->siesta = new Siesta();
        try {

            $this->getConfiguration();
            $output->writeln("I'm using configfile " . $this->config->getConfigFileName());

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

        } catch (ConnectException $ce) {
            $this->output->writeln($ce->getMessage());
            $this->output->writeln("Config file used " . Config::getInstance()->getConfigFileName());
            $this->output->writeln("Connection Configuration");
            $this->output->writeln((string)$ce->getConnectionData());
        } catch (InvalidConfigurationException $ic) {
            $this->output->writeln($ic->getMessage());
            $this->output->writeln("Please run 'vendor/bin/siesta init' to generate a config file");
        }
    }

    /**
     * @return void
     */
    protected function getConfiguration()
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
    protected function setupSiesta()
    {
        $logger = new SymphonyConsoleLogger($this->output);
        $this->siesta->setLogger($logger);
        $this->siesta->addFileType($this->generatorConfig->getEntityFileSuffix());
        $this->siesta->setColumnNamingStrategy($this->generatorConfig->getColumnNamingStrategyInstance());
        $connection = ConnectionFactory::getConnection($this->generatorConfig->getConnectionName());
        $this->siesta->setConnection($connection);
    }

}