<?php

namespace siestaphp\tests\functional;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\generator\ValidationLogger;
use siestaphp\util\File;
use siestaphp\xmlreader\XMLReader;

/**
 * Class MigrationATest
 * @package siestaphp\tests\functional
 */
class MigrationIndexTest extends SiestaTester
{

    const ASSET_PATH = "/migrationindex";

    const SRC_XML = "/Original.test.xml";

    const TARGET_XML = "/Target.test.xml";

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

    public function testMigrateReferences()
    {

        // 2nd generation triggers migration of database
        $this->generateEntityFile(self::ASSET_PATH, self::TARGET_XML);

        // read model
        $this->logger = new CodeceptionLogger();
        $dmc = new DataModelContainer(new ValidationLogger($this->logger));
        $xmlReader = new XMLReader(new File(__DIR__ . self::ASSET_PATH . self::TARGET_XML));
        $dmc->addEntitySourceList($xmlReader->getEntitySourceList());
        $dmc->updateModel();
        $dmc->validate();
        $artistEntiy = $dmc->getEntityByClassname("Customer");
        $this->assertNotNull($artistEntiy);



    }


}