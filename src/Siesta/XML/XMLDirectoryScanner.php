<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Model\ValidationLogger;
use Siesta\Util\File;

class XMLDirectoryScanner
{
    const ERROR_FILE_DOES_NOT_EXIST = "File '%s' does not exist. Skipping.";

    /**
     * @var ValidationLogger
     */
    protected $validationLogger;

    /**
     * @var XMLEntity[]
     */
    protected $xmlEntityList;

    /**
     * DirectoryScanner constructor.
     *
     * @param ValidationLogger $logger
     */
    public function __construct(ValidationLogger $logger)
    {
        $this->validationLogger = $logger;
        $this->xmlEntityList = [];
    }

    /**
     * @param File $file
     */
    public function addFile(File $file)
    {
        if (!$file->exists()) {
            $warn = sprintf(self::ERROR_FILE_DOES_NOT_EXIST, $file->getAbsoluteFileName());
            $this->validationLogger->warn($warn, 0);
            return;
        }
        $this->processFile($file);
    }

    /**
     * @param string $fileExtension
     * @param null $baseDir
     */
    public function addFileExtension(string $fileExtension, string $baseDir = null)
    {
        $baseDir = $baseDir ? $baseDir : getcwd();

        $base = new File($baseDir);
        if (!$base->exists()) {
            $warn = sprintf(self::ERROR_FILE_DOES_NOT_EXIST, $base->getAbsoluteFileName());
            $this->validationLogger->warn($warn, 0);
            return;
        }

        $fileList = $base->findFileList($fileExtension);
        foreach ($fileList as $file) {
            $this->processFile($file);
        }
    }

    /**
     * @param File $file
     */
    protected function processFile(File $file)
    {
        $xmlReader = new XMLReader($file);
        $xmlEntityList = $xmlReader->getEntityList();

        $this->xmlEntityList = array_merge($this->xmlEntityList, $xmlEntityList);
    }

    /**
     * @return XMLEntity[]
     */
    public function getXmlEntityList()
    {
        return $this->xmlEntityList;
    }
    
}