<?php

use gen\collector1n\ArtistEntity;
use gen\collector1n\LabelEntity;

/**
 * Class ReferenceTest
 */
class JSONTest extends \SiestaTester
{

    const DATABASE_NAME = "JSON_TEST";

    const ASSET_PATH = "/json";

    const SRC_XML = "/JSON.test.xml";

    const TEST_JSON = "/json/source.json";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/json/ArtistEntity.php",
            "/gen/json/LabelEntity.php"
        ));

    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testJSONLoad()
    {
        $jsonFile = new \siestaphp\util\File(__DIR__ . self::TEST_JSON);
        $jsonString = $jsonFile->getContents();
        $jsonArray = json_decode($jsonString, true);

        $label = new LabelEntity();
        $label->fromJSON($jsonString);

        $this->assertSame($label->getId(), $jsonArray["id"]);
        $this->assertSame($label->getName(), $jsonArray["name"]);
        $this->assertSame($label->getCity(), $jsonArray["city"]);

        $artistList = $label->getArtistList();
        $definitionList = $jsonArray["artistList"];
        $this->assertSame(sizeof($artistList), sizeof($jsonArray["artistList"]));

        for ($i = 0; $i < sizeof($artistList); $i++) {
            $this->assertSame($artistList[$i]->getId(), $definitionList[$i]["id"]);
            $this->assertSame($artistList[$i]->getName(), $definitionList[$i]["name"]);

        }

    }

}