<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\multipk\gen\ArtistEntity;
use siestaphp\tests\functional\multipk\gen\LabelEntity;

/**
 * Class MultiPKTest
 */
class IndexTest extends SiestaTester
{

    const DATABASE_NAME = "INDEX_TEST";

    const ASSET_PATH = "/index";

    const SRC_XML = "/Index.test.xml";

    /**
     * @var \siestaphp\driver\Driver
     */
    protected $driver;

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    public function testIndexList()
    {

    }



}