<?php

namespace SiestaTest\End2End\Reference;

use Siesta\Util\File;
use SiestaTest\End2End\Reference\Generated\Album;
use SiestaTest\End2End\Reference\Generated\AlbumMPK;
use SiestaTest\End2End\Reference\Generated\AlbumMPKService;
use SiestaTest\End2End\Reference\Generated\AlbumService;
use SiestaTest\End2End\Reference\Generated\AlbumUUID;
use SiestaTest\End2End\Reference\Generated\AlbumUUIDService;
use SiestaTest\End2End\Reference\Generated\Artist;
use SiestaTest\End2End\Reference\Generated\ArtistMPK;
use SiestaTest\End2End\Reference\Generated\ArtistUUID;
use SiestaTest\End2End\Util\End2EndTest;

class ReferenceTest extends End2EndTest
{

    public function setUp(): void
    {
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/reference.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    /**
     *
     */
    public function testReference()
    {

        $artist = new Artist();
        $artist->setName("Daft Punk");

        $album = new Album();
        $album->setName("Discovery");
        $album->setArtist($artist);

        $album->save(true);

        $service = AlbumService::getInstance();

        $albumReloaded = $service->getEntityByPrimaryKey($album->getId());
        $this->assertNotNull($albumReloaded);

        $artistReloaded = $albumReloaded->getArtist();
        $this->assertNotNull($artistReloaded);
        $this->assertSame($artist->getName(), $artistReloaded->getName());

        $albumReloaded->setArtist(null);
        $albumReloaded->save();

        $albumReloaded = $service->getEntityByPrimaryKey($album->getId());
        $artistNot = $albumReloaded->getArtist();

        $this->assertNull($artistNot);
    }

    /**
     *
     */
    public function testUUIDReference()
    {
        $artist = new ArtistUUID();
        $artist->setName("Daft Punk");

        $album = new AlbumUUID();
        $album->setName("Discovery");
        $album->setArtist($artist);

        $album->save(true);

        $service = AlbumUUIDService::getInstance();

        $albumReloaded = $service->getEntityByPrimaryKey($album->getId());
        $this->assertNotNull($albumReloaded);

        $artistReloaded = $albumReloaded->getArtist();
        $this->assertNotNull($artistReloaded);
        $this->assertSame($artist->getName(), $artistReloaded->getName());

        $albumReloaded->setArtist(null);
        $albumReloaded->save();

        $albumReloaded = $service->getEntityByPrimaryKey($album->getId());
        $artistNot = $albumReloaded->getArtist();

        $this->assertNull($artistNot);
    }

    /**
     *
     */
    public function testReferenceFromJSON()
    {
        $jsonAlbum = file_get_contents(__DIR__ . "/schema/album.json");
        $album = new Album();
        $album->fromJSON($jsonAlbum);

        $this->assertNull($album->getId());
        $this->assertSame("Discovery", $album->getName());
        $this->assertSame(1, $album->getArtistId());

        $artist = $album->getArtist();
        $this->assertNotNull($artist);
        $this->assertSame(1, $artist->getId());
        $this->assertSame("Daft Punk", $artist->getName());

    }

    /**
     *
     */
    public function testReferenceToJSON()
    {
        $artist = new Artist();
        $artist->setName("Daft Punk");

        $album = new Album();
        $album->setName("Discovery");
        $album->setArtist($artist);

        $this->assertSame('{"id":null,"name":"Discovery","artistId":1,"artist":{"id":1,"name":"Daft Punk"}}', $album->toJSON());
    }

    /**
     *
     */
    public function testReferenceMultiPK()
    {
        $artist = new ArtistMPK();
        $artist->setName("Daft Punk");

        $album = new AlbumMPK();
        $album->setName("Discovery");
        $album->setArtist($artist);

        $album->save(true);

        // reload the album
        $service = AlbumMPKService::getInstance();

        $albumReloaded = $service->getEntityByPrimaryKey($album->getId_1(), $album->getId_2());
        $this->assertNotNull($albumReloaded);

        $artistReloaded = $albumReloaded->getArtist();
        $this->assertNotNull($artistReloaded);
        $this->assertSame($artist->getName(), $artistReloaded->getName());

        // set reference to null
        $albumReloaded->setArtist(null);
        $albumReloaded->save();

        $albumReloaded = $service->getEntityByPrimaryKey($album->getId_1(), $album->getId_2());
        $artistNot = $albumReloaded->getArtist();

        $this->assertNull($artistNot);
    }

}