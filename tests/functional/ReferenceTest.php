<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\tests\functional\reference\gen\ArtistEntity;
use siestaphp\tests\functional\reference\gen\ArtistEntityManager;
use siestaphp\tests\functional\reference\gen\LabelEntity;

/**
 * Class ReferenceTest
 */
class ReferenceTest extends SiestaTester
{

    const ASSET_PATH = "/reference";

    const SRC_XML = "/Reference.test.xml";

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

    public function testReferencedEntity()
    {
        $label = new LabelEntity();
        $label->setName("K7");
        $label->setCity("Berlin");

        $artist = new ArtistEntity();
        $artist->setLabel($label);
        $artist->setName("Richard Dorfmeister");
        $artist->setDob(\siestaphp\runtime\Factory::newDateTime());

        // save artist cascading
        $artist->save(true);

        // check that the entity can be loaded again
        $artistLoaded = ArtistEntityManager::getInstance()->getEntityByPrimaryKey($artist->getId());
        $this->assertNotNull($artistLoaded, "Could not load generated artist");

        // check that the referenced label can be loaded
        $labelLoaded = $artistLoaded->getLabel();
        $this->assertNotNull($labelLoaded, "Could not load associated label");
        $this->assertSame($labelLoaded->getName(), $label->getName(), "Label not correctly loaded");

    }

    /**
     * tests the save cascade
     */
    public function testSaveCascade()
    {
        $label = new LabelEntity();
        $label->setName("K7");
        $label->setCity("Berlin");
        $artist = new ArtistEntity();
        $artist->setLabel($label);
        $artist->setName("Richard Dorfmeister");
        $artist->setDob(\siestaphp\runtime\Factory::newDateTime());

        $artist->save(true);

        // check that the entity can be loaded again
        $artistLoaded = ArtistEntityManager::getInstance()->getEntityByPrimaryKey($artist->getId());
        $this->assertNotNull($artistLoaded, "Could not load generated artist");

        // check that the referenced label can be loaded
        $labelLoaded = $artistLoaded->getLabel();
        $this->assertNotNull($labelLoaded, "Could not load associated label");
        $this->assertSame($labelLoaded->getName(), $label->getName(), "Label not correctly loaded");

    }

}