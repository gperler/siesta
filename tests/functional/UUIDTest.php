<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\uuid\gen\ArtistEntity;
use siestaphp\tests\functional\uuid\gen\LabelEntity;

/**
 * Class UUIDTest
 */
class UUIDTest extends SiestaTester
{

    const DATABASE_NAME = "UUID_TEST";

    const ASSET_PATH = "/uuid";

    const SRC_XML = "/UUID.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();
    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testArePKIdentical()
    {
        $artist = new ArtistEntity();
        $artist->getId(true);

        $this->assertTrue($artist->arePrimaryKeyIdentical($artist), "Are Primary Key does not identify identity");

        $other = new ArtistEntity();
        $other->getId(true);

        $this->assertFalse($artist->arePrimaryKeyIdentical($other), "Are Primary Key does not identify identity");

    }

    public function testStorage()
    {
        $artist = new ArtistEntity();
        $artist->save();

        $artistLoaded = ArtistEntity::getEntityByPrimaryKey($artist->getId());

        $this->assertNotNull($artistLoaded, "Artist could not be loaded");

        ArtistEntity::deleteEntityByPrimaryKey($artist->getId());
        $artistLoaded = ArtistEntity::getEntityByPrimaryKey($artist->getId());

        $this->assertNull($artistLoaded, "Artist could not be deleted");

    }

    public function testReference()
    {
        $label = new LabelEntity();
        $label->setCity("Berlin");

        $artist = new ArtistEntity();
        $artist->setName("Kruder & Dorfmeister");
        $artist->setLabel($label);
        $artist->save(true);

        $artistLoaded = ArtistEntity::getEntityByPrimaryKey($artist->getId(), $artist->getName());
        $this->assertNotNull($artistLoaded, "Artist could not be loaded");
        $this->assertSame($artistLoaded->getName(), $artist->getName());

        $labelLoaded = $artistLoaded->getLabel();

        $this->assertNotNull($labelLoaded, "Label could not be loaded");

        $this->assertSame($labelLoaded->getCity(), $label->getCity());

    }

    public function testCollection()
    {

        $kd = new ArtistEntity();
        $kd->setName("Kruder & Dorfmeister");

        $upBustleNOut = new ArtistEntity();
        $upBustleNOut->setName("Up Bustle & Out");

        $tosca = new ArtistEntity();
        $tosca->setName("Tosca");

        $label = new LabelEntity();
        $label->setCity("Berlin");

        $label->addToArtistList($kd);
        $label->addToArtistList($upBustleNOut);
        $label->addToArtistList($tosca);

        $label->save(true);

        $labelLoaded = LabelEntity::getEntityByPrimaryKey($label->getId(), $label->getName());
        $artistListLoaded = $labelLoaded->getArtistList();

        $this->assertSame(sizeof($artistListLoaded), 3, "not found all artist anymore");

    }

}