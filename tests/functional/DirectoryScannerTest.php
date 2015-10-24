<?php

namespace siestaphp\tests\functional;

use siestaphp\util\File;
use siestaphp\xmlreader\DirectoryScanner;

/**
 * Class MultiPKTest
 */
class DirectoryScannerTest extends SiestaTester
{

    const ASSET_PATH = "/directoryscanner";

    const SRC_FILE = "/test.file";

    const TEST_FILE = "test.xml";

    public function testFile()
    {
        // test filename
        $file = new File(__DIR__ . self::ASSET_PATH . "/" . self::TEST_FILE);
        $this->assertSame($file->getFileName(), self::TEST_FILE, "filename not identical");

        // test put content
        $content = "Soul II Soul";
        $file->putContents($content);
        $this->assertSame($content, $file->getContents(), "content not found");

        // test delete
        $file->delete();
        $this->assertFalse($file->exists(), "file should not exist anymore");

        // test scan dir
        $assetDir = new File(__DIR__ . self::ASSET_PATH  );
        $fileList = $assetDir->scanDir();
        $this->assertSame(2, sizeof($fileList), "not 3 files found");

        // test find file
        $scanXML = $assetDir->findFile("test2.scan.xml");
        $this->assertNotNull($scanXML, "file not found");
    }

    public function testScanner()
    {

        $logger = new CodeceptionLogger(true);
        $directoryScanner = new DirectoryScanner($logger);

        $entitySourceList = $directoryScanner->scan(__DIR__ . self::ASSET_PATH, "scan.xml");

        $this->assertNotNull($entitySourceList, "No entity source list found");
        $this->assertSame(sizeof($entitySourceList), 2, "not 2 entities found");
    }

    public function testInvalidDirectory()
    {
        $logger = new CodeceptionLogger(true);
        $directoryScanner = new DirectoryScanner($logger);

        $entitySourceList = $directoryScanner->scan("/" . md5("Jamie Woon"), "scan.xml");

        $this->assertSame(sizeof($entitySourceList), 0, "not 0 entities found");
        $logger->isErrorCodeSet(DirectoryScanner::VALIDATION_ERROR_INVALID_BASE_DIR);

        $entitySourceList = $directoryScanner->scan(__DIR__ . self::ASSET_PATH . self::SRC_FILE, "scan.xml");

        $this->assertSame(sizeof($entitySourceList), 0, "not 0 entities found");
        $logger->isErrorCodeSet(DirectoryScanner::VALIDATION_ERROR_INVALID_BASE_DIR);

    }

    public function testXMLException()
    {
        $logger = new CodeceptionLogger(true);
        $directoryScanner = new DirectoryScanner($logger);

        $entitySourceList = $directoryScanner->scan(__DIR__ . self::ASSET_PATH, "corrupt.xml");

        $this->assertNotNull($entitySourceList, "No entity source list found");
        $this->assertSame(sizeof($entitySourceList), 0, "not 0 entities found");

        $this->assertTrue($logger->isErrorCodeSet(DirectoryScanner::VALIDATION_ERROR_INVALID_XML));
    }

}