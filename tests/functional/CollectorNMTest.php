<?php

namespace siestaphp\tests\functional;

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