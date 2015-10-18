<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
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
class MigrationPKTest extends SiestaTester
{

    const ASSET_PATH = "/migrationa";

    const SRC_XML = "/MigrationPK.test.xml";

    const TABLE = "CREATE TABLE IF NOT EXISTS `ARTIST`(`id` INT NULL,`name` VARCHAR(100) NULL,`city` VARCHAR(100) NOT NULL,`zip` INT NULL ,PRIMARY KEY (`id`))";

    protected function setUp()
    {
        //        $this->connectAndInstall(self::DATABASE_NAME);
        //
        //        $this->connection->query(self::TABLE);
        //
        //        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    public function testMigrationPrimaryKey()
    {

    }

}