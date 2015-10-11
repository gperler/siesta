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
class GeneratorCommand extends Command
{
    protected function configure()
    {
        $this->setName('generate');
        $this->setDescription('Scans directories for entity files and generates classes and database tables');
        $this->addOption('suffix', null, InputOption::VALUE_OPTIONAL, 'Suffix of Entitiy definition files. Default is ' . DirectoryScanner::DEFAULT_SUFFIX);
        $this->addOption('basePath', null, InputOption::VALUE_OPTIONAL, 'Base path in which to searchs for entity xml files');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $start = -microtime(true);
        // get input
        $suffix = $input->getOption('suffix');
        $basePath = $input->getOption("basePath") ? $input->getOption("basePath") : getcwd();

        $driver = \siestaphp\runtime\ServiceLocator::getDriver(array(
            "user" => "root",
            "password" => "",
            "port" => 3306,
            "database" => "spryker2",
            "host" => "127.0.0.1"
        ));

        // create symphony wrapper
        $log = new SymphonyConsoleOutputWrapper($output);

        // do the work
        $generator = new Generator($log, $suffix);
        $generator->generate($basePath);

        $delta = $start + microtime(true);

        $output->writeln($delta*1000);

    }
}