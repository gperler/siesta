<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\namespacetest\base\ns2\Artist;
use siestaphp\tests\functional\namespacetest\base\ns4\Label;

/**
 * Class ReferenceTest
 */
class NamespaceTest extends SiestaTester
{

    const DATABASE_NAME = "NAMESPACE_TEST";

    const ASSET_PATH = "/namespacetest";

    const SRC_XML = "/Namespace.test.xml";

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

    public function testNamespaces()
    {
        $label = new Label();
        $label->getArtistList(true);

        $artist = new Artist();
        $artist->getLabel(true);

    }

}