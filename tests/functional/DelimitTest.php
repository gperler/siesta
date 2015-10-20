<?php

namespace siestaphp\tests\functional;

/**
 * Class MultiPKTest
 */
class DelimitTest extends SiestaTester
{

    const ASSET_PATH = "/delimit";

    const SRC_XML = "/Delimit.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall();

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