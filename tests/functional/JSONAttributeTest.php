<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\jsonattribute\gen\Album;

/**
 * Class JSONAttributeTest
 * @package siestaphp\tests\functional
 */
class JSONAttributeTest extends SiestaTester
{

    const ASSET_PATH = "/jsonattribute";

    const SRC_XML = "/JSONAttribute.test.xml";

    const TEST_JSON = "/json/source.json";

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

    public function testJSONAttribute()
    {
        $album = new Album();
        $json = $album->getAttributeList();
        $this->assertNull($json, "Attribute list not null");


        $album->addToAttributeList("Jamie", "woon");
        $album->addToAttributeList("Mirror", array("writing"));

        $woon = $album->getFromAttributeList("Jamie");

        $this->assertSame("woon", $woon, "did not return woon");

        $album->save();

        $albumReloaded = Album::getEntityByPrimaryKey($album->getId());

        $woon = $albumReloaded->getFromAttributeList("Jamie");
        $this->assertSame("woon", $woon, "did not return woon");



    }

}