<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\collectorfilter\gen\ArtistEntity;
use siestaphp\tests\functional\collectorfilter\gen\LabelEntity;

/**
 * Class ReferenceTest
 */
class CollectorFilterTest extends SiestaTester
{

    const ASSET_PATH = "/collectorfilter";

    const SRC_XML = "/CollectorFilter.test.xml";

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

    public function testCollectorFilter()
    {
        $jamieWoon = new ArtistEntity();
        $jamieWoon->setName("Jamie Woon");

        $jamieLidell = new ArtistEntity();
        $jamieLidell->setName("Jamie Lidell");

        $yyy = new ArtistEntity();
        $yyy->setName("Yeah Yeah Yeahs");

        $label = new LabelEntity();
        $label->addToArtistList($jamieWoon);
        $label->addToArtistList($jamieLidell);
        $label->addToArtistList($yyy);

        $label->save(true);


        $artistList = $label->getArtistListFilterByName("%Jamie%");

        $this->assertSame(2, sizeof($artistList), "not exactly 2 artist found");



    }

    /**
     * tests the save cascade
     */
    public function testSaveCascade()
    {

    }

}