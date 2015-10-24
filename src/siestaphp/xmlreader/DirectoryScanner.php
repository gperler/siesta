<?php

namespace siestaphp\xmlreader;

use Psr\Log\LoggerInterface;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\exceptions\XMLNotValidException;
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EntitySource[]
     */
    protected $entitySourceList;

    /**
     * @var string
     */
    protected $fileSuffix;

    /**
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->entitySourceList = array();
        $this->logger = $logger;
    }

    /**
     * @param string $basedir
     * @param string $fileSuffix
     *
     * @return EntitySource[]
     */
    public function scan($basedir, $fileSuffix = null)
    {
        $this->fileSuffix = $fileSuffix;


        $baseDirFile = new File($basedir);

        if (!$baseDirFile->exists()) {
            $this->logger->error("Basedir " . $baseDirFile . " does not exist", array("code" => self::VALIDATION_ERROR_INVALID_BASE_DIR));
            return array();
        }

        if (!$baseDirFile->isDir()) {
            $this->logger->error("Basedir " . $baseDirFile . " is not a directory", array("code" => self::VALIDATION_ERROR_INVALID_BASE_DIR));
            return array();

        }

        $this->logger->info("I'm searching in " . $baseDirFile . " for *." . $fileSuffix);

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
        if (!$file->isType($this->fileSuffix)) {
            return;
        }

        $this->logger->info("Found " . $file->getAbsoluteFileName());

        try {
            $xmlReader = new XMLReader($file);
            $this->entitySourceList = array_merge($this->entitySourceList, $xmlReader->getEntitySourceList());
        } catch (XMLNotValidException $e) {

            $this->logger->error("Parsing file " . $e->getFileName(), array("code" => self::VALIDATION_ERROR_INVALID_XML));
            foreach ($e->getErrorList() as $error) {
                $this->logger->error($error, array("code" => self::VALIDATION_ERROR_INVALID_XML));
            }

        }
    }

}