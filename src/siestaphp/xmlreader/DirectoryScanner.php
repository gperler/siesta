<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\exceptions\XMLNotValidException;
use siestaphp\generator\GeneratorConfig;
use siestaphp\generator\ValidationLogger;
use siestaphp\util\File;

/**
 * Class DirectoryScanner
 * @package siestaphp\generator
 */
class DirectoryScanner
{

    const VALIDATION_ERROR_INVALID_XML = 1000;

    const VALIDATION_ERROR_INVALID_BASE_DIR = 1001;

    /**
     * @var ValidationLogger
     */
    protected $generatorLog;

    /**
     * @var EntitySource[]
     */
    protected $entitySourceList;

    /**
     * @var GeneratorConfig
     */
    protected $config;

    /**
     *
     */
    public function __construct()
    {
        $this->entitySourceList = array();
    }

    /**
     * @param ValidationLogger $log
     * @param GeneratorConfig $config
     *
     * @return EntitySource[]
     */
    public function scan(ValidationLogger $log, GeneratorConfig $config)
    {

        $this->config = $config;

        $this->generatorLog = $log;

        $baseDirFile = new File($config->getBaseDir());

        if (!$baseDirFile->exists()) {
            $log->error("Basedir " . $baseDirFile . " does not exist", self::VALIDATION_ERROR_INVALID_BASE_DIR);
            return null;
        }

        if (!$baseDirFile->isDir()) {
            $log->error("Basedir " . $baseDirFile . " is not a directory", self::VALIDATION_ERROR_INVALID_BASE_DIR);
            return null;

        }

        $log->info("I'm searching in " . $baseDirFile . " for *." . $this->config->getEntityFileSuffix());

        $this->handleDirectory($baseDirFile);

        return $this->entitySourceList;
    }

    /**
     * @param File $directory
     *
     * @return void
     */
    private function handleDirectory(File $directory)
    {
        $fileList = $directory->scanDir();
        foreach ($fileList as $file) {

            if ($file->isDir()) {
                $this->handleDirectory($file);
            }

            if ($file->isFile()) {
                $this->handleEntityFile($file);
            }
        }
    }

    /**
     * @param File $file
     *
     * @return void
     */
    private function handleEntityFile(File $file)
    {
        if (!$file->isType($this->config->getEntityFileSuffix())) {
            return;
        }

        $this->generatorLog->info("Found " . $file->getAbsoluteFileName());

        try {
            $xmlReader = new XMLReader($file);
            $this->entitySourceList = array_merge($this->entitySourceList, $xmlReader->getEntitySourceList());
        } catch (XMLNotValidException $e) {

            $this->generatorLog->error("Parsing file " . $e->getFileName(), self::VALIDATION_ERROR_INVALID_XML);
            foreach ($e->getErrorList() as $error) {
                $this->generatorLog->error($error, self::VALIDATION_ERROR_INVALID_XML);
            }

        }
    }

}