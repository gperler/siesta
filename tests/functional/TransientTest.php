<?php

namespace siestaphp\tests\functional;

use siestaphp\runtime\Factory;
use siestaphp\tests\functional\transient\gen\ArtistEntity;

/**
 * Class AttributeTest
 */
class TransientTest extends SiestaTester
{

    const DATABASE_NAME = "TRANSIENT_TEST";

    const ASSET_PATH = "/transient";

    const SRC_XML = "/Transient.test.xml";

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

    /**
     * @return int
     */
    public function testCreateEntity()
    {
        $artist = new ArtistEntity();
        $artist->setName("Blondie");
        $artist->setTransientBool(true);
        $artist->setTransientInt(77);
        $artist->setTransientFloat(19.08);
        $artist->setTransientString("data");
        $artist->setTransientDateTime(Factory::newDateTime());
        $artist->save();

        $artistReloaded = ArtistEntity::getEntityByPrimaryKey($artist->getId());

        $this->assertNotNull($artistReloaded, "Artist could not be reloaded");
        $this->assertNull($artistReloaded->getTransientBool(), "bool not null");
        $this->assertNull($artistReloaded->getTransientInt(), "int not null");
        $this->assertNull($artistReloaded->getTransientFloat(), "float not null");
        $this->assertNull($artistReloaded->getTransientString(), "string not null");
        $this->assertNull($artistReloaded->getTransientDateTime(), "Datetime not null");
    }

}