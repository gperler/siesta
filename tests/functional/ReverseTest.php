<?php

use \gen\attribute\ArtistEntity;

require_once "SiestaTester.php";

/**
 * Class AttributeTest
 */
class ReverseTest extends \SiestaTester
{

    const DATABASE_NAME = "REVERSE_TEST";

    const ASSET_PATH = "/reverse.test";

    const SRC_XML = "/Reverse.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array());
    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    /**
     * @return int
     */
    public function testCreateEntity()
    {
    }

 }