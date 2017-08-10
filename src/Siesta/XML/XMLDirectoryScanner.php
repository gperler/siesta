<?php
declare(strict_types=1);

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
     * @var XMLEntityExtension[]
     */
    protected $xmlEntityExtensionList;

    /**
     * DirectoryScanner constructor.
     *
     * @param ValidationLogger $logger
     */
    public function __construct(ValidationLogger $logger)
    {
        $this->validationLogger = $logger;
        $this->xmlEntityList = [];
        $this->xmlEntityExtensionList = [];
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
     * @param string $baseDir
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
        $this->xmlEntityList = array_merge($this->xmlEntityList, $xmlReader->getEntityList());
        $this->xmlEntityExtensionList = array_merge($this->xmlEntityExtensionList, $xmlReader->getEntityExtensionList());
    }

    /**
     * @return XMLEntity[]
     */
    public function getXmlEntityList()
    {
        $this->mergeExtensionToEntity();
        return $this->xmlEntityList;
    }

    /**
     *
     */
    protected function mergeExtensionToEntity()
    {
        foreach ($this->xmlEntityExtensionList as $xmlEntityExtension) {
            $tableName = $xmlEntityExtension->getTableName();
            if (!$tableName === null) {
                $this->validationLogger->warn("found extension without tablename", 0);
                continue;
            }

            $xmlEntity = $this->getXMLEntityByTableName($xmlEntityExtension->getTableName());
            if ($xmlEntity === null) {
                $this->validationLogger->warn("Extension for " . $tableName . " no entity found.", 0);
                continue;
            }
            $xmlEntity->addExtension($xmlEntityExtension);
        }
    }

    /**
     * @param string $tableName
     *
     * @return null|XMLEntity
     */
    protected function getXMLEntityByTableName(string $tableName)
    {
        foreach ($this->xmlEntityList as $xmlEntity) {
            if ($xmlEntity->getTableName() === $tableName) {
                return $xmlEntity;
            }
        }
        return null;

    }
}