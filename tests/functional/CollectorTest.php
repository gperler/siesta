<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\collector1n\gen\ArtistEntity;
use siestaphp\tests\functional\collector1n\gen\LabelEntity;


/**
 * Class ReferenceTest
 */
class CollectorTest extends SiestaTester
{

    const DATABASE_NAME = "ONE_N_COLLECTOR_TEST";

    const ASSET_PATH = "/collector1n";

    const SRC_XML = "/Collector-1n.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);
    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    // tests
    public function testCollection()
    {

        $labelId = $this->createStructure();

        $this->loadStructure($labelId);

        $this->loadLabelFromArtist();

        $this->deleteAllArtistsFromLabe($labelId);
    }

    /**
     * @return int
     */
    private function createStructure()
    {
        $tosca = new ArtistEntity();
        $tosca->setName("Tosca");

        $herbalizer = new ArtistEntity();
        $herbalizer->setName("Herbalizer");

        $erlend = new ArtistEntity();
        $erlend->setName("Erlend Øye");

        $k7 = new LabelEntity();
        $k7->setName("K7");
        $k7->setCity("Berlin");

        $k7->addToArtistList($erlend);
        $k7->addToArtistList($tosca);
        $k7->addToArtistList($herbalizer);

        $k7->save(true);

        return $k7->getId();
    }

    /**
     * @param int $labelId
     */
    private function loadStructure($labelId)
    {
        $k7 = LabelEntity::getEntityByPrimaryKey($labelId);

        $artistList = $k7->getArtistList();

        $this->assertSame(sizeof($artistList), 3, "Not all artist loaded again");
    }

    private function loadLabelFromArtist()
    {
        $artist = ArtistEntity::getEntityByPrimaryKey(1);

        $this->assertNotNull($artist, "Artist not found");

        $label = $artist->getLabel();

        $this->assertNotNull($label, "Label for Artist " . $artist->getName() . " not found");

        $artistList = $label->getArtistList();

        $this->assertSame(sizeof($artistList), 3, "Not all artist loaded again");

    }

    /**
     * @param int $labelId
     */
    private function deleteAllArtistsFromLabe($labelId)
    {
        $k7 = LabelEntity::getEntityByPrimaryKey($labelId);
        $k7->deleteAllArtistList();

        $artistList = $k7->getArtistList();
        $this->assertSame(sizeof($artistList), 0, "Not all artist deleted again");

    }

}