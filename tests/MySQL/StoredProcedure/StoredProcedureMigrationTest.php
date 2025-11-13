<?php

namespace SiestaTest\Functional\MySQL\Connection;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;
use Siesta\Migration\DatabaseMigrator;
use Siesta\Migration\Migrator;
use SiestaTest\TestUtil\DataModelHelper;

class StoredProcedureMigrationTest extends Unit
{

    protected function setUp(): void
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    /**
     * @param $schemaFile
     *
     */
    protected function readModelAndMigrate(string $schemaFile)
    {
        $connection = ConnectionFactory::getInstance()->getConnection();
        $this->assertNotNull($connection);
        $dmh = new DataModelHelper();
        $model = $dmh->readModel($schemaFile);

        $connection = ConnectionFactory::getConnection();

        $migrator = new Migrator();
        $migrator->migrateDirect($model, $connection);

    }

    public function testMigration()
    {

        $this->readModelAndMigrate(__DIR__ . "/schema/migration.before.test.xml");

        $connection = ConnectionFactory::getConnection();

        $dmh = new DataModelHelper();
        $model = $dmh->readModel(__DIR__ . "/schema/migration.chnage.test.xml");
        $migrator = new DatabaseMigrator($model, $connection);
        $migrator->createAlterStatementList(true);
        $alterList = $migrator->getAlterStoredProcedureStatementList();


        $this->assertCount(5, $alterList);

        // artist table has a attribute change : insert und update need to be recreated
        $this->assertSame("DROP PROCEDURE IF EXISTS `Artist_insert`", $alterList[0]);
        $this->assertSame("CREATE PROCEDURE `Artist_insert`(IN P_ID INT, IN P_COLUMN1 VARCHAR(101), IN P_COLUMN2 DATETIME) MODIFIES SQL DATA SQL SECURITY INVOKER BEGIN INSERT INTO `Artist` ( `id`, `column1`, `column2` ) VALUES ( P_ID, P_COLUMN1, P_COLUMN2 ) ON DUPLICATE KEY UPDATE `column1` = P_COLUMN1, `column2` = P_COLUMN2; END;", $alterList[1]);

        $this->assertSame("DROP PROCEDURE IF EXISTS `Artist_update`", $alterList[2]);
        $this->assertSame("CREATE PROCEDURE `Artist_update`(IN P_ID INT,IN P_COLUMN1 VARCHAR(101),IN P_COLUMN2 DATETIME) MODIFIES SQL DATA SQL SECURITY INVOKER BEGIN UPDATE `Artist` SET `id` = P_ID,`column1` = P_COLUMN1,`column2` = P_COLUMN2 WHERE `id` = P_ID; END;", $alterList[3]);

        $this->assertSame("DROP PROCEDURE IF EXISTS Label_customStoredProcedure2;", $alterList[4]);
    }


}