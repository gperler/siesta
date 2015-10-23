<?php

namespace siestaphp\tests\functional;

/**
 * Class PerformanceTest
 */
class PerformanceTest extends SiestaTester
{

    const ASSET_PATH = "/performance";

    const SRC_XML = "/Performance.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();
    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testInsert()
    {

    }

}