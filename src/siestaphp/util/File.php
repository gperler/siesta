<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 13:59
 */

namespace siestaphp\util;

use siestaphp\util\StringUtil;

/**
 * Class File is a Wrapper Class for file operations
 * @package siestaphp\util
 */
class File
{

    /**
     * absolute path to file
     * @var string
     */
    protected $absoluteFileName;

    /**
     * @param string $absoluteFileName
     */
    public function __construct($absoluteFileName)
    {
        $this->absoluteFileName = rtrim($absoluteFileName, '/');
    }

    /**
     * @return string
     */
    public function getAbsoluteFileName()
    {
        return $this->absoluteFileName;
    }

    /**
     * tells if the file exists
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->absoluteFileName);
    }

    /**
     * tells if the file is a directory
     * @return bool
     */
    public function isDir()
    {
        return is_dir($this->absoluteFileName);
    }

    /**
     * tells if the file is a file
     * @return bool
     */
    public function isFile()
    {
        return is_file($this->absoluteFileName);
    }

    /**
     * tries to delete a file
     * @return bool
     */
    public function delete()
    {
        if (!$this->exists()) {
            return false;
        }
        return unlink($this->absoluteFileName);
    }

    /**
     * creates needed directories recursively
     *
     * @param int $mode
     *
     * @return bool
     */
    public function createDir($mode = 0777)
    {
        return is_dir($this->absoluteFileName) || mkdir($this->absoluteFileName, $mode, true);
    }

    /**
     * tells if the file is of type
     *
     * @param string $type
     *
     * @return bool
     */
    public function isType($type)
    {
        return StringUtil::endsWith($this->absoluteFileName, $type);
    }

    /**
     * scans a directory and returns a list of Files
     * @return File[]
     */
    public function scanDir()
    {
        // only possible in directories
        if (!$this->isDir()) {
            return null;
        }

        $fileList = array();
        $fileNameList = scandir($this->absoluteFileName);
        foreach ($fileNameList as $fileName) {
            // do not add . and ..
            if ($fileName === "." or $fileName === "..") {
                continue;
            }
            $absoluteFileName = $this->absoluteFileName . "/" . $fileName;
            $fileList [] = new File ($absoluteFileName);
        }

        return $fileList;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return file_get_contents($this->absoluteFileName);
    }

    /**
     * loads the file as XML DOMDocument
     * @return \DOMDocument
     */
    public function loadAsXML()
    {
        $xml = new \DomDocument ();
        $xml->load($this->absoluteFileName);
        return $xml;
    }

    /**
     * loads the file as XSLT Processor
     * @return \XSLTProcessor
     */
    public function loadAsXSLTProcessor()
    {
        $xsl = new \XsltProcessor ();
        $xsl->importStylesheet($this->loadAsXML());
        return $xsl;
    }
}
