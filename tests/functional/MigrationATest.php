<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\migrator\DatabaseMigrator;
use siestaphp\migrator\Migrator;
use siestaphp\util\File;
use siestaphp\util\Util;
use siestaphp\xmlreader\XMLReader;

/**
 * Class MigrationATest
 * @package siestaphp\tests\functional
 */
class MigrationATest extends SiestaTester
{

    const DATABASE_NAME = "MIGRATIONA_TEST";

    const ASSET_PATH = "/migrationa";

    const SRC_XML = "/MigrationA.test.xml";


    const TABLE = "CREATE TABLE IF NOT EXISTS `ARTIST`(`id` INT NULL,`name` VARCHAR(100) NULL,`city` VARCHAR(100) NOT NULL,`zip` INT NULL ,PRIMARY KEY (`id`))";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->connection->query(self::TABLE);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        //$this->dropDatabase();

    }

    public function testMigration() {

        // read model
        $this->logger = new CodeceptionLogger();
        $dmc = new DataModelContainer($this->logger);
        $xmlReader = new XMLReader(new File(__DIR__ . self::ASSET_PATH . self::SRC_XML));
        $dmc->addEntitySourceList($xmlReader->getEntitySourceList());
        $dmc->updateModel();
        $dmc->validate();

        $migrator = new Migrator($dmc, $this->connection);
        $migrator->migrate();



    }


}