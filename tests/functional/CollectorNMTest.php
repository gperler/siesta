<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\migrator\AttributeListMigrator;
use siestaphp\tests\functional\collectornm\gen\Album;
use siestaphp\tests\functional\collectornm\gen\Artist;

/**
 * Class ReferenceTest
 */
class CollectorNMTest extends SiestaTester
{

    const DATABASE_NAME = "N_M_COLLECTOR_TEST";

    const ASSET_PATH = "/collectornm";

    const SRC_XML = "/CollectorNM.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();
    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    // tests
    public function testCollection()
    {
        $jamieWoon = new Artist();
        $jamieWoon->setName("Jamie Woon");
        $jamieWoon->save();

        $mirrorwriting = new Album();
        $mirrorwriting->setName("Mirrorwriting");
        $mirrorwriting->addToArtistList($jamieWoon);
        $mirrorwriting->save(true);

        $makingtime = new Album();
        $makingtime->setName("making time");
        $makingtime->addToArtistList($jamieWoon);
        $makingtime->save(true);


        $albumList = $jamieWoon->getAlbumList(true);
        $this->assertSame(2, sizeof($albumList), "not exactly 2 album found");


        $radical = new Album();
        $radical->setName("Radical");

        $monte = new Artist();
        $monte->setName("Monte");
        $monte->addToAlbumList($radical);
        $monte->save(true);

        $jamieWoon->deleteAllAlbumList();


        $monteAlbumList = $monte->getAlbumList(true);

        $this->assertSame(1, sizeof($monteAlbumList), "expected one album from monte");




    }

}