<?php

use gen\collector1n\ArtistEntity;
use gen\collector1n\LabelEntity;

/**
 * Class ReferenceTest
 */
class NamespaceTest extends \SiestaTester
{

    const DATABASE_NAME = "NAMESPACE_TEST";

    const ASSET_PATH = "/namespace.test";

    const SRC_XML = "/Namespace.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/ns1/ArtistEntity.php",
            "/gen/ns2/Artist.php",
            "/gen/ns3/LabelEntity.php",
            "/gen/ns4/Label.php"
        ));
    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testNamespaces()
    {
        $label = new \gen\ns4\Label();
        $label->getArtistList(true);

        $artist = new \gen\ns2\Artist();
        $artist->getLabel();

    }

}