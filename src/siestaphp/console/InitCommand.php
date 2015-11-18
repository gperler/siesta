<?php

namespace siestaphp\console;

use siestaphp\Config;
use siestaphp\driver\Connection;
use siestaphp\driver\ConnectionData;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\exceptions\InvalidConfiguration;
use siestaphp\util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class GreetCommand
 * @package AppBundle\Command
 */
class InitCommand extends Command
{

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var QuestionHelper
     */
    protected $questionHelper;

    protected function configure()
    {
        $this->setName('init');
        $this->setDescription('Creates a config file for siesta');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->questionHelper = $this->getQuestionHelper();

        do {
            $cd = $this->askConnectionDetails();
        } while (!$this->isConnectionValid($cd) and !$this->continueWithInvalidData());

        $targetPath = $this->askConfigTargetPath();

        $this->generateConfiguration($cd, $targetPath);
    }

    /**
     * @param ConnectionData $cd
     * @param string $targetPath
     */
    private function generateConfiguration(ConnectionData $cd, $targetPath)
    {
        // create dir
        $dir = new File(getcwd() . "/" . $targetPath);
        $dir->createDir();

        $xsl = new File(__DIR__ . "/xslt/ConfigFile.xsl");
        $processor = $xsl->loadAsXSLTProcessor();

        $processor->setParameter("", "name", $cd->name);
        $processor->setParameter("", "driver", "siestaphp\\driver\\mysqli\\MySQLDriver");
        $processor->setParameter("", "host", $cd->host);
        $processor->setParameter("", "port", $cd->port);
        $processor->setParameter("", "database", $cd->database);
        $processor->setParameter("", "user", $cd->user);
        $processor->setParameter("", "password", $cd->password);
        $processor->setParameter("", "charset", $cd->charSet);

        $processor->transformToUri(new \DOMDocument(), $dir->getAbsoluteFileName() . "/" . Config::CONFIG_FILE_NAME);

        $this->output->writeln("Config file generated in " . $targetPath);
    }

    /**
     * @return string
     */
    private function askConfigTargetPath()
    {

        $directoryList = [];
        $this->compileDirectoryList($directoryList, getcwd());

        $question = new Question('<question>Please enter target path for configuration</question> ', '');
        $question->setAutocompleterValues($directoryList);

        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * @return bool
     */
    private function continueWithInvalidData()
    {
        $question = new ConfirmationQuestion('<question>Connection not possible, use connection data anyway? (y/n)</question>', false);
        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * @return ConnectionData
     */
    private function askConnectionDetails()
    {
        $cd = new ConnectionData();

        $cd->name = $this->askQuestion("Please enter connection name", "default");
        $cd->host = $this->askQuestion("Please enter host", "127.0.0.1");
        $cd->port = $this->askQuestion("Please enter port", "3306");
        $cd->driver = $this->askQuestion("Please enter driver", "mysql");
        $cd->database = $this->askQuestion("Please enter database name", "test");
        $cd->user = $this->askQuestion("Please enter user", "root");
        $cd->password = $this->askQuestion("Please enter password", "");
        $cd->charSet = $this->askQuestion("Please enter charset", "utf8");

        return $cd;
    }

    /**
     * @param ConnectionData $cd
     *
     * @return Connection
     */
    private function isConnectionValid(ConnectionData $cd)
    {
        try {
            ConnectionFactory::addConnection($cd);
            $this->output->writeln("Connection successful");
            return true;
        } catch (InvalidConfiguration $e) {
            $this->output->writeln("Error " . $e->getMessage());
            return false;
        } catch (ConnectException $e) {
            $this->output->writeln("Error " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param $question
     * @param $default
     *
     * @return string
     */
    private function askQuestion($question, $default)
    {
        $q = new Question("<question>$question ($default)</question> ", $default);
        return $this->questionHelper->ask($this->input, $this->output, $q);
    }

    /**
     * @return QuestionHelper
     */
    private function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    /**
     * @param array $directoryList
     * @param string $currentDirectory
     */
    private function compileDirectoryList(&$directoryList, $currentDirectory)
    {
        $dir = new File($currentDirectory);
        $fileList = $dir->scanDir();
        foreach ($fileList as $file) {
            if (!$file->isDir()) {
                continue;
            }
            $directoryList[] = str_replace(getcwd() . "/", "", $file->getAbsoluteFileName());
            $this->compileDirectoryList($directoryList, $file->getAbsoluteFileName());
        }

    }

}