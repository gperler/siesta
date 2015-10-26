<?php

namespace siestaphp\console;

use siestaphp\Config;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\exceptions\InvalidConfiguration;
use siestaphp\generator\GeneratorConfig;
use siestaphp\generator\ReverseGenerator;
use siestaphp\generator\ReverseGeneratorConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GreetCommand
 * @package AppBundle\Command
 */
class ReverseGeneratorCommand extends Command
{

    const NO_CONFIG_FILE = "<error>No config file found. Run 'vendor/bin/siesta init' to generate one. </error>";

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ReverseGeneratorConfig
     */
    protected $reverseConfig;

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

    protected function configure()
    {
        $this->setName("reverse");
        $this->setDescription("Scans directories for entity files and generates classes and database tables");

        $this->addOption(GeneratorCommand::OPTION_CONFIG_FILE, null, InputOption::VALUE_OPTIONAL, "Path to config file to use.");
        $this->addOption(ReverseGeneratorConfig::OPTION_CONNECTION_NAME, null, InputOption::VALUE_OPTIONAL, "Name of the connection to use");
        $this->addOption(ReverseGeneratorConfig::OPTION_ENTITY_FILE_SUFFX, null, InputOption::VALUE_OPTIONAL, "Suffix of Entitiy definition files. Default is " . GeneratorConfig::DEFAULT_ENTITY_SUFFIX);
        $this->addOption(ReverseGeneratorConfig::OPTION_SINGLE_FILE, null, InputOption::VALUE_OPTIONAL, "Indicates if all entities should be stored in a single file");
        $this->addOption(ReverseGeneratorConfig::OPTION_TARGET_PATH, null, InputOption::VALUE_OPTIONAL, "Targetpath to where the reverse engineered XML are stored to.");
        $this->addOption(ReverseGeneratorConfig::OPTION_TARGET_NAMESPACE, null, InputOption::VALUE_OPTIONAL, "Target namespace for generated classes.");

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

            $output->writeln("Reverse engineering database " . $this->reverseConfig->getConnectionName());

            $log = new SymphonyConsoleOutputWrapper($output);

            // do the work
            $generator = new ReverseGenerator($log);

            $generator->generateXML($this->reverseConfig);

            $this->endTimer();
        } catch (ConnectException $ce) {
            $this->output->writeln($ce->getMessage());
            $this->output->writeln("Config file used " . Config::getInstance()->getConfigFileName());
            $this->output->writeln("Connection Configuration");
            $this->output->writeln((string) $ce->getConnectionData());
        } catch (InvalidConfiguration $ic) {
            $this->output->writeln($ic->getMessage());
        }

    }

    protected function configureGenerator()
    {
        foreach (ReverseGeneratorConfig::$OPTION_LIST as $option) {
            $optionValue = $this->input->getOption($option);
            if ($optionValue !== null) {
                $this->reverseConfig->setValue($option, $optionValue);
            }

        }
    }

    /**
     * @return void
     */
    protected function getConfig()
    {
        $configFileName = $this->input->getOption(GeneratorCommand::OPTION_CONFIG_FILE);

        if ($configFileName === null) {
            $this->setConfig(Config::getInstance());
            return;
        }

        $this->setConfig(Config::getInstance($configFileName));
    }

    /**
     * @param Config $config
     */
    protected function setConfig(Config $config)
    {
        if ($config === null) {
            $this->output->writeln(self::NO_CONFIG_FILE);
            exit();
        }

        $this->config = $config;
        $this->reverseConfig = $config->getReverseGeneratorConfig();
    }

    /**
     * @return void
     */
    protected function startTimer()
    {
        $this->startTime = microtime(true);
    }

    /**
     * @return void
     */
    protected function endTimer()
    {
        $delta = (microtime(true) - $this->startTime) * 1000;
        $this->output->writeln(sprintf("Generation complete in %0.2fms", $delta));
    }
}