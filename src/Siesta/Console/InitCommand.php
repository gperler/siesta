<?php

namespace Siesta\Console;

use Siesta\Config\Config;
use Siesta\Database\ConnectionData;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Exception\ConnectException;
use Siesta\Driver\MySQL\MySQLDriver;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @author Gregor Müller
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

    /**
     *
     */
    protected function configure(): void
    {
        $this->setName('init');
        $this->setDescription('Creates a config file for siesta');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->questionHelper = $this->getQuestionHelper();

        do {
            $cd = $this->askConnectionDetails();
        } while (!$this->isConnectionValid($cd) and !$this->continueWithInvalidData());

        $targetPath = $this->askConfigTargetPath();

        $this->generateConfiguration($cd, $targetPath);
        return 0;
    }

    /**
     * @param ConnectionData $connectionData
     * @param string $targetPath
     */
    private function generateConfiguration(ConnectionData $connectionData, string $targetPath): void
    {

        $file = new File($targetPath . '/' . Config::CONFIG_FILE_NAME);
        $file->createDirForFile();

        $configuration = Config::buildConfiguration($connectionData);

        $file->putContents(json_encode($configuration, JSON_PRETTY_PRINT));

        $this->output->writeln("Config file generated in " . $targetPath);
    }

    /**
     * @return string
     */
    private function askConfigTargetPath(): string
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
    private function continueWithInvalidData(): bool
    {
        $question = new ConfirmationQuestion('<question>Connection not possible, use connection data anyway? (y/n)</question>', false);
        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * @return ConnectionData
     */
    private function askConnectionDetails(): ConnectionData
    {
        $cd = new ConnectionData();

        $cd->name = $this->askQuestion("Please enter connection name", "default");
        $cd->host = $this->askQuestion("Please enter host", "127.0.0.1");
        $cd->port = $this->askIntegerQuestion("Please enter port", 3306);
        $cd->driver = $this->askQuestion("Please enter driver", MySQLDriver::DRIVER_CLASS);
        $cd->database = $this->askQuestion("Please enter database name", "test");
        $cd->user = $this->askQuestion("Please enter user", "root");
        $cd->password = $this->askQuestion("Please enter password", "");
        $cd->charSet = $this->askQuestion("Please enter charset", "utf8");

        return $cd;
    }

    /**
     * @param ConnectionData $cd
     *
     * @return bool
     */
    private function isConnectionValid(ConnectionData $cd): bool
    {
        try {
            ConnectionFactory::addConnection($cd);
            $this->output->writeln("Connection successful");
            return true;
        } catch (InvalidConfigurationException|ConnectException $e) {
            $this->output->writeln("Error " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $question
     * @param string $default
     *
     * @return string
     */
    private function askQuestion(string $question, string $default): string
    {
        $q = new Question("<question>$question ($default)</question> ", $default);
        return $this->questionHelper->ask($this->input, $this->output, $q);
    }

    /**
     * @param string $question
     * @param int $default
     *
     * @return int
     */
    private function askIntegerQuestion(string $question, int $default): int
    {
        $q = new Question("<question>$question ($default)</question> ", $default);
        $stringValue = $this->questionHelper->ask($this->input, $this->output, $q);
        return (int)$stringValue;
    }

    /**
     * @return QuestionHelper
     */
    private function getQuestionHelper(): QuestionHelper
    {
        return $this->getHelper('question');
    }

    /**
     * @param array $directoryList
     * @param string $currentDirectory
     */
    private function compileDirectoryList(array &$directoryList, string $currentDirectory): void
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