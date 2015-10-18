<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;

/**
 * Class ForeignIsPrimary
 */
class ForeignIsPrimaryTest extends SiestaTester
{

    const ASSET_PATH = "/foreignkeyisprimary";

    const SRC_XML = "/ForeignIsPrimary.test.xml";

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

    public function testForeignIsPrimary()
    {
        Debug::debug("FOREIGN IS PRIAMRY");
    }

}