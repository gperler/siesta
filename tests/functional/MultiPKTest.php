<?php

use gen\collector1n\ArtistEntity;
use gen\collector1n\LabelEntity;

/**
 * Class MultiPKTest
 */
class MultiPKTest extends \SiestaTester
{

    const DATABASE_NAME = "MULTI_PK_TEST";

    const ASSET_PATH = "/multipk.test";

    const SRC_XML = "/MultiPK.test.xml";

    /**
     * @var \siestaphp\driver\Driver
     */
    protected $driver;

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/multipk/ArtistEntity.php",
            "/gen/multipk/LabelEntity.php"
        ));

    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    public function testArePKIdentical()
    {
        $artist = new \gen\multipk\ArtistEntity();
        $artist->getId(true);
        $artist->getName(true);

        $this->assertTrue($artist->arePrimaryKeyIdentical($artist), "Are Primary Key does not identify identity");

        $other = new \gen\multipk\ArtistEntity();
        $other->getId(true);
        $other->getName(true);
        $this->assertFalse($artist->arePrimaryKeyIdentical($other), "Are Primary Key does not identify identity");

    }

    public function testStorage()
    {
        $artist = new \gen\multipk\ArtistEntity();
        $artist->save();

        $artistLoaded = \gen\multipk\ArtistEntity::getEntityByPrimaryKey($artist->getId(), $artist->getName());

        $this->assertNotNull($artistLoaded, "Artist could not be loaded");

        \gen\multipk\ArtistEntity::deleteEntityByPrimaryKey($artist->getId(), $artist->getName());
        $artistLoaded = \gen\multipk\ArtistEntity::getEntityByPrimaryKey($artist->getId(), $artist->getName());

        $this->assertNull($artistLoaded, "Artist could not be deleted");

    }

    public function testReference()
    {
        $label = new \gen\multipk\LabelEntity();
        $label->setCity("Berlin");

        $artist = new \gen\multipk\ArtistEntity();
        $artist->setDisplayName("Kruder & Dorfmeister");
        $artist->setLabel($label);
        $artist->save(true);

        $artistLoaded = \gen\multipk\ArtistEntity::getEntityByPrimaryKey($artist->getId(), $artist->getName());
        $this->assertNotNull($artistLoaded, "Artist could not be loaded");
        $this->assertSame($artistLoaded->getDisplayName(), $artist->getDisplayName());

        $labelLoaded = $artistLoaded->getLabel();

        $this->assertNotNull($labelLoaded, "Label could not be loaded");

        $this->assertSame($labelLoaded->getCity(), $label->getCity());

    }

    public function testCollection()
    {

        $kd = new \gen\multipk\ArtistEntity();
        $kd->setDisplayName("Kruder & Dorfmeister");

        $upBustleNOut = new \gen\multipk\ArtistEntity();
        $upBustleNOut->setDisplayName("Up Bustle & Out");

        $tosca = new \gen\multipk\ArtistEntity();
        $tosca->setDisplayName("Tosca");

        $label = new \gen\multipk\LabelEntity();
        $label->setCity("Berlin");

        $label->addToArtistList($kd);
        $label->addToArtistList($upBustleNOut);
        $label->addToArtistList($tosca);

        $artistList = $label->getArtistList();
        $label->save(true);

        $labelLoaded = \gen\multipk\LabelEntity::getEntityByPrimaryKey($label->getId(), $label->getName());
        $artistListLoaded = $labelLoaded->getArtistList();

        $this->assertSame(sizeof($artistListLoaded), 3, "not found all artist anymore");

        for ($i = 0; $i < sizeof($artistListLoaded); $i++) {
            $this->assertSame($artistListLoaded[$i]->getDisplayName(), $artistList[$i]->getDisplayName());
            $this->assertSame($artistListLoaded[$i]->getName(), $artistList[$i]->getName());
            $this->assertSame($artistListLoaded[$i]->getId(), $artistList[$i]->getId());

        }

    }

}