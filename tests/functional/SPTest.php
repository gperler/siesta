<?php
namespace siestaphp\tests\functional;

use siestaphp\tests\functional\storedprocedure\gen\ArtistEntity;

/**
 * Class ReferenceTest
 */
class SPTest extends SiestaTester
{

    const ASSET_PATH = "/storedprocedure";

    const SRC_XML = "/SP.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->generateData();

        $this->assertNoValidationErrors();
    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    private function generateData()
    {
        $kd = new ArtistEntity();
        $kd->setName("Kruder & Dorfmeister");
        $kd->setCity("Vienna");
        $kd->save();

        $dk = new ArtistEntity();
        $dk->setName("dZihan & Kamien");
        $dk->setCity("Vienna");
        $dk->save();

    }

    public function testSingleResultSP()
    {
        $firstArtist = ArtistEntity::getFirstArtistByCity("Vienna");

        $this->assertNotNull($firstArtist);
        $this->assertInstanceOf("siestaphp\\tests\\functional\\storedprocedure\\gen\\ArtistEntity", $firstArtist, "Not instance of ArtistEntity");

    }

    public function testListResultSP()
    {
        $artistList = ArtistEntity::getArtistByCity("Vienna");

        $this->assertSame(sizeof($artistList), 2, "not 2 artist found");

        foreach ($artistList as $artist) {
            $this->assertInstanceOf("siestaphp\\tests\\functional\\storedprocedure\\gen\\ArtistEntity", $artist, "Not instance of ArtistEntity");

        }
    }

    public function testResultSetSP()
    {
        $count = null;
        $countArtistResult = ArtistEntity::countArtistInCity("Vienna");
        while ($countArtistResult->hasNext()) {
            $count = $countArtistResult->getIntegerValue("COUNT(ID)");
        }
        $countArtistResult->close();
        $this->assertSame($count, 2, "Count not right");
    }

}