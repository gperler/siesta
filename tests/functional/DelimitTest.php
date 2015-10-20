<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\delimit\gen\Artist;

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
        $artist = new Artist();
        $artist->setBool(true);
        $artist->setInt(123);
        $artist->setFloat(19.08);
        $artist->save();

        $artist->setString("Gregor");
        $artist->save();

        $artist->setString("Müller");
        $artist->save();



    }

}