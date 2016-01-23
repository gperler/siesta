<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\constructfactory\gen\ns3\ArtistEntityService;
use siestaphp\tests\functional\constructfactory\gen\ns4\LabelEntityService;

/**
 * Class ReferenceTest
 */
class ConstructTest extends SiestaTester
{

    const ASSET_PATH = "/constructfactory";

    const SRC_XML = "/Construct.test.xml";

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

    public function testNamespaces()
    {
        $artist = ArtistEntityService::getInstance()->newInstance();
        $this->assertTrue($artist->getTestValue(), "test value not set to true for artist");

        $label = LabelEntityService::getInstance()->newInstance();
        $this->assertTrue($label->getTestValue(), "test value not set to true for label");
    }

}