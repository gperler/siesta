<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\collector1n\gen\ArtistEntity;
use siestaphp\tests\functional\collector1n\gen\LabelEntity;

/**
 * Class ReferenceTest
 */
class CollectorNMTest extends SiestaTester
{

    const DATABASE_NAME = "N_M_COLLECTOR_TEST";

    const ASSET_PATH = "/collectornm";

    const SRC_XML = "/CollectorNM.test.xml";

    protected function setUp()
    {

//        $this->connectAndInstall(self::DATABASE_NAME);
//
//        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);
//
//        $this->assertNoValidationErrors();
    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    // tests
    public function testCollection()
    {


    }


}