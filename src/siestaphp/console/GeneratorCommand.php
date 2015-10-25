<?php

namespace siestaphp\console;

use siestaphp\Config;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\exceptions\InvalidConfiguration;
use siestaphp\generator\Generator;
use siestaphp\generator\GeneratorConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
     * @var GeneratorConfig
     */
    protected $generatorConfig;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var float
     */
    protected $startTime;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate');
        $this->setDescription('Scans directories for entity files and generates classes and database tables');
        $this->addOption(self::OPTION_CONFIG_FILE, null, InputOption::VALUE_OPTIONAL, "Path to config file to use.");

        $this->addOption(GeneratorConfig::OPTION_DROP_UNUSED_TABLES, null, InputOption::VALUE_OPTIONAL, "tell if unused tables should be dropped. Default no.");
        $this->addOption(GeneratorConfig::OPTION_BASE_DIR, null, InputOption::VALUE_OPTIONAL, "use this basepath for finding entity files.");
        $this->addOption(GeneratorConfig::OPTION_CONNECTION_NAME, null, InputOption::VALUE_OPTIONAL, "name of the database connection to use.");
        $this->addOption(GeneratorConfig::OPTION_ENTITY_FILE_SUFFX, null, InputOption::VALUE_OPTIONAL, "suffix of schema/entity files");

        $this->addOption(GeneratorConfig::OPTION_MIGRATION_METHOD, null, InputOption::VALUE_OPTIONAL, "migration method can be: direct, sql or php");
        $this->addOption(GeneratorConfig::OPTION_MIGRATION_TARGET_PATH, null, InputOption::VALUE_OPTIONAL, "path where sql or php file are written to");
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
        try {

            $this->startTimer();

            $this->getConfig();
            $output->writeln("I'm using configfile " . $this->config->getConfigFileName());

            $this->configureGenerator();

            $log = new SymphonyConsoleOutputWrapper($output);

            // do the work
            $generator = new Generator($log, $this->config->getGeneratorConfig());

            $generator->generate();

            $this->endTimer();
        } catch (ConnectException $ce) {
            $this->output->writeln($ce->getMessage());
            $this->output->writeln("Config file used " . Config::getInstance()->getConfigFileName());
            $this->output->writeln("Connection Configuration");
            $this->output->writeln((string) $ce->getConnectionData());
        } catch (InvalidConfiguration $ic) {
            $this->output->writeln($ic->getMessage());
            $this->output->writeln("Please run 'vendor/bin/siesta init' to generate a config file");
        }
    }

    protected function configureGenerator()
    {
        foreach (GeneratorConfig::$OPTION_LIST as $option) {
            $this->generatorConfig->setValue($option, $this->input->getOption($option));
        }
    }

    /**
     * @return void
     */
    protected function getConfig()
    {
        $configFileName = $this->input->getOption(self::OPTION_CONFIG_FILE);

        if ($configFileName === null) {
            $this->setConfig(Config::getInstance());
            return;
        }

        $this->setConfig(Config::getInstance($configFileName));
    }

    /**
     * @param Config $config
     */
    protected function setConfig(Config $config) {
        if ($config === null) {
            $this->output->writeln(self::NO_CONFIG_FILE);
            exit();
        }

        $this->config = $config;
        $this->generatorConfig = $config->getGeneratorConfig();
    }

    /**
     * @return void
     */
    protected function startTimer() {
        $this->startTime = microtime(true);
    }

    /**
     * @return void
     */
    protected function endTimer() {
        $delta = (microtime(true) - $this->startTime) * 1000;
        $this->output->writeln(sprintf("Generation complete in %0.1fms", $delta));
    }
}