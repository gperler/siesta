<?php

/**
 * Class PerformanceTest
 */
class PerformanceTest extends \SiestaTester
{

    const DATABASE_NAME = "PERFORMANCE_TEST";

    const ASSET_PATH = "/performance";

    const SRC_XML = "/Performance.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array());
    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testInsert()
    {

    }

}