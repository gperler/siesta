<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\exceptions\XMLNotValidException;
use siestaphp\generator\GeneratorLog;
use siestaphp\util\File;

/**
 * Class DirectoryScanner
 * @package siestaphp\generator
 */
class DirectoryScanner
{

    const VALIDATION_ERROR_INVALID_XML = 1000;

    const VALIDATION_ERROR_INVALID_BASE_DIR = 1001;

    const DEFAULT_SUFFIX = "entity.xml";

    /**
     * @var File
     */
    protected $baseDirFile;

    /**
     * @var GeneratorLog
     */
    protected $generatorLog;

    /**
     * @var EntitySource[]
     */
    protected $entitySourceList;

    /**
     * @var string
     */
    protected $fileSuffix;

    /**
     *
     */
    public function __construct()
    {
        $this->entitySourceList = array();
    }

    /**
     * @param GeneratorLog $log
     * @param string $baseDir
     * @param string $suffix
     *
     * @return EntitySource[]
     */
    public function scan(GeneratorLog $log, $baseDir, $suffix = self::DEFAULT_SUFFIX)
    {

        $this->fileSuffix = $suffix;

        $this->generatorLog = $log;

        $this->baseDirFile = new File($baseDir);

        if (!$this->baseDirFile->exists()) {
            $log->error("Basedir " . $baseDir . " does not exist", self::VALIDATION_ERROR_INVALID_BASE_DIR);
            return null;
        }

        if (!$this->baseDirFile->isDir()) {
            $log->error("Basedir " . $baseDir . " is not a directory", self::VALIDATION_ERROR_INVALID_BASE_DIR);
            return null;

        }

        $log->info("Searching in " . $baseDir . " for *." . $this->fileSuffix);

        $this->handleDirectory($this->baseDirFile);

        return $this->entitySourceList;
    }

    /**
     * @param File $directory
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
     */
    private function handleEntityFile(File $file)
    {
        if (!$file->isType($this->fileSuffix)) {
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