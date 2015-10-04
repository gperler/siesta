<?php

use gen\collector1n\ArtistEntity;
use gen\collector1n\LabelEntity;

/**
 * Class UUIDTest
 */
class UUIDTest extends \SiestaTester
{

    const DATABASE_NAME = "UUID_TEST";

    const ASSET_PATH = "/uuid.test";

    const SRC_XML = "/UUID.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/uuid/ArtistEntity.php",
            "/gen/uuid/LabelEntity.php"
        ));

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testArePKIdentical()
    {
        $artist = new \gen\uuid\ArtistEntity();
        $artist->getId(true);

        $this->assertTrue($artist->arePrimaryKeyIdentical($artist), "Are Primary Key does not identify identity");

        $other = new \gen\uuid\ArtistEntity();
        $other->getId(true);

        $this->assertFalse($artist->arePrimaryKeyIdentical($other), "Are Primary Key does not identify identity");

    }

    public function testStorage()
    {
        $artist = new \gen\uuid\ArtistEntity();
        $artist->save();

        $artistLoaded = \gen\uuid\ArtistEntity::getEntityByPrimaryKey($artist->getId());

        $this->assertNotNull($artistLoaded, "Artist could not be loaded");

        \gen\uuid\ArtistEntity::deleteEntityByPrimaryKey($artist->getId());
        $artistLoaded = \gen\uuid\ArtistEntity::getEntityByPrimaryKey($artist->getId());

        $this->assertNull($artistLoaded, "Artist could not be deleted");

    }

    public function testReference()
    {
        $label = new \gen\uuid\LabelEntity();
        $label->setCity("Berlin");

        $artist = new \gen\uuid\ArtistEntity();
        $artist->setName("Kruder & Dorfmeister");
        $artist->setLabel($label);
        $artist->save(true);

        $artistLoaded = \gen\uuid\ArtistEntity::getEntityByPrimaryKey($artist->getId(), $artist->getName());
        $this->assertNotNull($artistLoaded, "Artist could not be loaded");
        $this->assertSame($artistLoaded->getName(), $artist->getName());

        $labelLoaded = $artistLoaded->getLabel();

        $this->assertNotNull($labelLoaded, "Label could not be loaded");

        $this->assertSame($labelLoaded->getCity(), $label->getCity());

    }

    public function testCollection()
    {

        $kd = new \gen\uuid\ArtistEntity();
        $kd->setName("Kruder & Dorfmeister");

        $upBustleNOut = new \gen\uuid\ArtistEntity();
        $upBustleNOut->setName("Up Bustle & Out");

        $tosca = new \gen\uuid\ArtistEntity();
        $tosca->setName("Tosca");

        $label = new \gen\uuid\LabelEntity();
        $label->setCity("Berlin");

        $label->addToArtistList($kd);
        $label->addToArtistList($upBustleNOut);
        $label->addToArtistList($tosca);

        $label->save(true);

        $labelLoaded = \gen\uuid\LabelEntity::getEntityByPrimaryKey($label->getId(), $label->getName());
        $artistListLoaded = $labelLoaded->getArtistList();

        $this->assertSame(sizeof($artistListLoaded), 3, "not found all artist anymore");

    }

}