<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\generator\GeneratorLog;
use siestaphp\util\File;

/**
 * Class DirectoryScanner
 * @package siestaphp\generator
 */
class DirectoryScanner
{

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
     *
     */
    public function __construct()
    {
        $this->entitySourceList = array();
    }

    /**
     * @param GeneratorLog $log
     * @param $baseDir
     *
     * @return EntitySource[]
     */
    public function scan(GeneratorLog $log, $baseDir)
    {

        $this->baseDirFile = new File($baseDir);

        if (!$this->baseDirFile->exists()) {
            $log->error("Basedir " . $baseDir . " does not exist");
            return null;
        }

        if (!$this->baseDirFile->isDir()) {
            $log->error("Basedir " . $baseDir . " is not a directory");
            return null;

        }

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
                $this->handleFile($file);
            }
        }
    }

    /**
     * @param File $file
     */
    private function handleFile(File $file)
    {

        if ($file->isType("entity.xml")) {
            $this->handleEntityFile($file);
        }
    }

    /**
     * @param File $file
     */
    private function handleEntityFile(File $file)
    {
        // read file
        $xmlReader = new XMLReader($file);

        $this->entitySourceList = array_merge($this->entitySourceList, $xmlReader->getEntitySourceList());

    }

}