<?php

use \gen\notnull\ArtistEntity;

/**
 * Class AttributeTest
 */
class NotNullTest extends \SiestaTester
{

    const DATABASE_NAME = "NOTNULL_TEST";

    const ASSET_PATH = "/notnull.test";

    const SRC_XML = "/NotNull.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/notnull/ArtistEntity.php"
        ));
    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testNotNull()
    {
        $artist = new ArtistEntity();
        $artist->save();
    }

    public function testBoolean()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setBool(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute Bool has not raised cannot be null");
    }

    public function testInt()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setInt(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute Int has not raised cannot be null");
    }

    public function testFloat()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setFloat(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute float has not raised cannot be null");
    }

    public function testString()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setString(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute string has not raised cannot be null");
    }

    public function testTime()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setPTime(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute Time has not raised cannot be null");
    }

    public function testDate()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setPDate(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute Date has not raised cannot be null");
    }

    public function testDateTime()
    {
        try {
            $artist = new ArtistEntity();
            $artist->setDateTime(null);
            $artist->save();
        } catch (\siestaphp\driver\exceptions\CannotBeNullException $e) {
            return;
        }
        $this->assertTrue(false, "Attribute DateTime has not raised cannot be null");
    }

}