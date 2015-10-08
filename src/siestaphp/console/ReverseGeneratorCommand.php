<?php

namespace siestaphp\console;

use siestaphp\generator\Generator;
use siestaphp\xmlreader\DirectoryScanner;
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
    protected function configure()
    {
        $this->setName('generate');
        $this->setDescription('Scans directories for entity files and generates classes and database tables');
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'Suffix of Entitiy definition files. Default is ' . DirectoryScanner::DEFAULT_SUFFIX);
        $this->addOption('targetPath', null, InputOption::VALUE_OPTIONAL, 'Suffix of Entitiy definition files. Default is ' . DirectoryScanner::DEFAULT_SUFFIX);

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("not implemented yet");
        return;
        // get input
        $suffix = $input->getOption('database');
        $targetPath = $input->getOption("targetPath") ? $input->getOption("targetPath") : getcwd();
        $singleFile = true;

        // create symphony wrapper
        $log = new SymphonyConsoleOutputWrapper($output);

        // do the work
        $generator = new Generator($log, $suffix);
        $generator->generate($basePath);
    }
}