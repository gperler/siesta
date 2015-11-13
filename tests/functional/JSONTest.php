<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\json\gen\LabelEntity;
use siestaphp\util\File;

/**
 * Class ReferenceTest
 */
class JSONTest extends SiestaTester
{

    const ASSET_PATH = "/json";

    const SRC_XML = "/JSON.test.xml";

    const TEST_JSON = "/json/source.json";

    protected function setUp()
    {
        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testJSONLoad()
    {
        $jsonFile = new File(__DIR__ . self::TEST_JSON);
        $jsonString = $jsonFile->getContents();
        $jsonArray = json_decode($jsonString, true);

        // initialize from json
        $label = new LabelEntity();
        $label->fromJSON($jsonString);

        // test label
        $this->assertSame($jsonArray["id"], $label->getId(), "label id not correct");
        $this->assertSame($jsonArray["name"], $label->getName(), "label name not correct");
        $this->assertSame($jsonArray["city"], $label->getCity(), "label city not correct");

        // test artist list
        $artistList = $label->getArtistList();
        $definitionList = $jsonArray["artistList"];
        $this->assertSame(sizeof($jsonArray["artistList"]), sizeof($artistList), "not exactly 2 artist found");

        for ($i = 0; $i < sizeof($artistList); $i++) {
            $this->assertSame($definitionList[$i]["id"], $artistList[$i]->getId(), "artist id not correct");
            $this->assertSame($definitionList[$i]["name"], $artistList[$i]->getName(), "artist name not correct");
            $this->assertSame($definitionList[$i]["transient"], $artistList[$i]->getTransient(), "artist transient not correct");
        }

    }

}