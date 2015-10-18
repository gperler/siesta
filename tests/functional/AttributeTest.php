<?php

namespace siestaphp\tests\functional;

use siestaphp\runtime\Factory;
use siestaphp\tests\functional\attribute\AttributeXML;
use siestaphp\tests\functional\attribute\gen\ArtistEntity;


/**
 * Class AttributeTest
 */
class AttributeTest extends SiestaTester
{

    const DATABASE_NAME = "ATTRIBUTE_TEST";

    const ASSET_PATH = "/attribute";

    const SRC_XML = "/Attribute.test.xml";

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

    /**
     * @return int
     */
    public function testCreateEntity()
    {
        echo "CREATE ENTITY";
        $artist = new ArtistEntity();
        $artist->setBool(true);
        $artist->setString("Test123");
        $artist->setFloat(19.77);
        $artist->setInt(42);

        // date time
        $dateTime = Factory::newDateTime();
        $dateTime->stringToTime("19-08-1977 10:11:12");
        $artist->setDateTime($dateTime);

        // date
        $date = Factory::newDateTime();
        $date->stringToTime("19-08-1977");
        $artist->setPDate($date);

        // time
        $time = Factory::newDateTime();
        $time->stringToTime("10:11:12");
        $artist->setPTime($time);

        $this->startTimer();
        $artist->save();
        $this->stopTimer("Time to save entity %0.2fms");

        $this->startTimer();
        $artistLoaded = ArtistEntity::getEntityByPrimaryKey($artist->getId());
        $this->stopTimer("Time to load entity %0.2fms");

        $this->assertSame($artist->getId(), $artistLoaded->getId(), "");
        $this->assertSame($artist->getBool(), $artistLoaded->getBool(), "");
        $this->assertSame($artist->getInt(), $artistLoaded->getInt(), "");
        $this->assertSame($artist->getFloat(), $artistLoaded->getFloat(), "");
        $this->assertSame($artist->getString(), $artistLoaded->getString());
        $this->assertTrue($artist->getDateTime()->equals($dateTime));
        $this->assertTrue($artist->getPDate()->equals($date));
        $this->assertTrue($artist->getPTime()->equals($time));

        // delete entity
        $this->startTimer();
        ArtistEntity::deleteEntityByPrimaryKey($artist->getId());
        $this->stopTimer("Time to delete entity %0.2fms");

        // try to load it
        $artistLoaded = ArtistEntity::getEntityByPrimaryKey($artist->getId());

        // check that it doesnt exist.
        $this->assertNull($artistLoaded);
    }

    /**
     * test that null values are stored correctly
     */
    public function testNull()
    {
        $artist = new ArtistEntity();

        $artist->setBool(null);
        $artist->setString(null);
        $artist->setFloat(null);
        $artist->setInt(null);
        $artist->setDateTime(null);
        $artist->setPDate(null);
        $artist->setPTime(null);

        // store it
        $artist->save();

        // load it again
        $artistLoaded = ArtistEntity::getEntityByPrimaryKey($artist->getId());

        // check that null is back
        $this->assertSame($artist->getId(), $artistLoaded->getId());
        $this->assertNull($artist->getBool());
        $this->assertNull($artist->getInt());
        $this->assertNull($artist->getFloat());
        $this->assertNull($artist->getString());
        $this->assertNull($artist->getDateTime());
        $this->assertNull($artist->getPDate());
        $this->assertNull($artist->getPTime());

        // delete entity
        ArtistEntity::deleteEntityByPrimaryKey($artist->getId());

    }

    /**
     * test the validation
     */
    public function testValidation()
    {
        $artist = new ArtistEntity();

        $artist->setId(1);
        $artist->setBool(true);
        $artist->setInt(77);
        $artist->setFloat(19.77);
        $artist->setString("Test");

        $this->assertTrue($artist->validate());

        $artist->setId("Test");
        $this->assertFalse($artist->validate());

        $artist->setId(1);
        $artist->setFloat("Test");
        $this->assertFalse($artist->validate());

    }

    public function testDefaultValues()
    {

        $definition = AttributeXML::getDefaultValues();

        $artist = new ArtistEntity();

        $this->assertSame($definition["id"], $artist->getId());
        $this->assertSame($definition["bool"], $artist->getBool());
        $this->assertSame($definition["int"], $artist->getInt());
        $this->assertSame($definition["float"], $artist->getFloat());
        $this->assertSame($definition["string"], $artist->getString());
        $this->assertTrue($artist->getDateTime()->equals($definition["dateTime"]));

    }

    public function testIDGeneration()
    {
        $artist = new ArtistEntity();
        $id = $artist->getId(true);
        $idTest = $artist->getId(true);

        $this->assertSame($id, $idTest, "IDs not identical");

    }

}