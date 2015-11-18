<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\constructfactory\gen\ns3\ArtistEntityManager;
use siestaphp\tests\functional\constructfactory\gen\ns4\LabelEntityManager;

/**
 * Class ReferenceTest
 */
class ManagerTest extends SiestaTester
{

    const ASSET_PATH = "/manager";

    const SRC_XML = "/Manager.test.xml";

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
//        $artist = ArtistEntityManager::getInstance()->newInstance();
//        $this->assertTrue($artist->getTestValue(), "test value not set to true for artist");
//
//        $label = LabelEntityManager::getInstance()->newInstance();
//        $this->assertTrue($label->getTestValue(), "test value not set to true for label");
    }

}