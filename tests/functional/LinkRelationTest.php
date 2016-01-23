<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\linkrelation\gen\LabelEntity;
use siestaphp\util\File;

/**
 * Class ReferenceTest
 */
class LinkRelation extends SiestaTester
{

    const ASSET_PATH = "/linkrelation";

    const SRC_XML = "/Link.relation.test.xml";

    const TEST_JSON = "/linkrelation/source.json";

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

    public function testLinkRelations()
    {
        $jsonFile = new File(__DIR__ . self::TEST_JSON);
        $jsonString = $jsonFile->getContents();

        $label = new LabelEntity();
        $label->fromJSON($jsonString);

        // id should be still null 
        //$this->assertNull($label->getId(), "ID must be null");

        // link the objects
        $label->linkRelations();

        // test the linking
        $this->assertNotNull($label->getId(), "ID must not be null");
        $this->assertSame($label->getTopSeller()->getId(), $label->getTopSellerId(), "1:1 not linked correctly");

        // test artist list elements
        foreach ($label->getArtistList() as $artist) {
            $this->assertSame($label->getId(), $artist->getLabelId(), "Linking not succeeded");
        }
    }

}