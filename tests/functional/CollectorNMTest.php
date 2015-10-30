<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\collectornm\gen\Album;
use siestaphp\tests\functional\collectornm\gen\Artist;
use siestaphp\tests\functional\collectornm\gen\ArtistSong;

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
        $artist = new Artist();
        $artist->setName("Jamie Woon");
        $artist->save();

        $album = new Album();
        $album->setName("Mirrorwriting");

        $album->addToArtistList($artist);
        $album->save(true);


        $albumList = $artist->getAlbumList(true);

        $this->assertSame(1, sizeof($albumList), "not exactly one album found");

        $this->assertSame($album->getId(), $albumList[0]->getId(), "id not identical");
        $this->assertSame($album->getName(), $albumList[0]->getName(), "name not identical");



    }


    /*
     * Artist {
     *     protected $songArtistList = array();
     *
     *     Album[] getAlbumList() { ... }
     *
     *     ArtistSong function addToAlbumList(Album $a) {
     *          $artistSong = new ArtistSong();
     *          $artistSong->setArtist($this);
     *          $artistSong->setAlbum($a);
     *
     *          $songArtistList[] = $artistSong;
     *
     *          return $artistSong
     *     }
     *
     *    void removeFromAlbumList(Album $a) {
     *         $songArtist = SongArtistManager->deleteWhere($a->getId(), $this->getId());
     *
     *    }
     *
     *    save() {
     *
     *       if ($cascade) ...
     *
     *       foreach($songArtistList) { save() }
     *
     *    }
     *
     *
     * }
     *
     *
     *
     */

}