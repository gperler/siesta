<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\exceptions\XMLNotValidException;
use siestaphp\generator\ValidationLogger;
use siestaphp\migrator\DatabaseMigrator;
use siestaphp\migrator\Migrator;
use siestaphp\util\File;
use siestaphp\util\Util;
use siestaphp\xmlreader\XMLReader;

/**
 * Class MigrationATest
 * @package siestaphp\tests\functional
 */
class MigrationRefTest extends SiestaTester
{

    const ASSET_PATH = "/migrationref";

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
        $this->generateEntityFile(self::ASSET_PATH, self::TARGET_XML);


    }

}