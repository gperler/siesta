<?php

namespace Siesta\Console;

use Siesta\Config\Config;
use Siesta\Config\ReverseConfig;
use Siesta\Database\Exception\ConnectException;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Main\Reverse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Gregor Müller
 */
class ReverseCommand extends Command
{

    const NO_CONFIG_FILE = "<error>No config file found. Run 'vendor/bin/siesta init' to generate one. </error>";

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ReverseConfig
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

    /**
     * @var Reverse
     */
    protected $reverse;

    protected function configure()
    {
        $this->setName("reverse");
        $this->setDescription("Build the Siesta XML schema for a database.");

        $this->addOption(GeneratorCommand::OPTION_CONFIG_FILE, null, InputOption::VALUE_OPTIONAL, "Path to config file to use.");
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
        $this->reverse = new Reverse();

        try {

            $this->getConfiguration();
            $this->configureReverse();

            if ($this->reverseConfig->isSingleFile()) {
                $this->reverse->createSingleXMLFile($this->reverseConfig->getTargetFile());
            } else {
                $this->reverse->createXMLFileForEveryTable($this->reverseConfig->getTargetPath());
            }

            $output->writeln("I'm using configfile " . $this->config->getConfigFileName());
            $output->writeln("Reverse engineering database " . $this->reverseConfig->getConnectionName());

        } catch (ConnectException $ce) {
            $this->output->writeln($ce->getMessage());
            $this->output->writeln("Config file used " . Config::getInstance()->getConfigFileName());
            $this->output->writeln("Connection Configuration");
            $this->output->writeln((string)$ce->getConnectionData());
        } catch (InvalidConfigurationException $ic) {
            $this->output->writeln($ic->getMessage());
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
        $this->reverseConfig = $this->config->getReverseConfig();
    }

    /**
     *
     */
    protected function configureReverse()
    {
        $this->reverse->setConnectionName($this->reverseConfig->getConnectionName());
        $this->reverse->setEntityXMLFileSuffix($this->reverseConfig->getEntityXMLFileSuffix());
        $this->reverse->setDefaultNamespace($this->reverseConfig->getDefaultNamespace());
        $this->reverse->setAttributeNamingStrategy($this->reverseConfig->getAttributeNamingInstance());
        $this->reverse->setClassNamingStrategy($this->reverseConfig->getClassNamingInstance());
    }

}