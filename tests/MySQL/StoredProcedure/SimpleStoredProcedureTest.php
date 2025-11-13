<?php

namespace SiestaTest\Functional\MySQL\Connection;

use Codeception\Test\Unit;
use Codeception\Util\Debug;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use SiestaTest\TestUtil\DataModelHelper;

class SimpleStoredProcedureTest extends Unit
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
     * @return DataModel
     */
    protected function readModelAndCreateTable($schemaFile): DataModel
    {
        $connection = ConnectionFactory::getInstance()->getConnection();
        $this->assertNotNull($connection);
        $dmh = new DataModelHelper();

        $model = $dmh->readModel($schemaFile);

        $statementFactory = $connection->getCreateStatementFactory();
        foreach ($model->getEntityList() as $entity) {
            $createTable = $statementFactory->buildCreateTable($entity);
            $connection->execute($createTable[0]);
        }
        return $model;
    }

    public function testCustomStoredProcedure()
    {
        $connection = ConnectionFactory::getInstance()->getConnection();

        $model = $this->readModelAndCreateTable(__DIR__ . "/schema/customStoredProcedure.test.xml");

        $artist = $model->getEntityByTableName("Artist");

        $sp = $artist->getStoredProcedureByName("customStoredProcedure");
        $this->assertNotNull($sp);

        $spFactory = $connection->getStoredProcedureFactory();
        $customSP = $spFactory->createCustomStoredProcedure($model, $artist, $sp);

        $this->assertNotNull($customSP);

        $drop = $customSP->getDropProcedureStatement();
        $create = $customSP->getCreateProcedureStatement();

        $connection->execute($drop);
        $connection->execute($create);

        $this->assertSame("CREATE PROCEDURE `Artist_customStoredProcedure`(IN P_PARAM1 VARCHAR(100), IN P_PARAM2 DATETIME) READS SQL DATA SQL SECURITY INVOKER BEGIN SELECT * FROM `Artist` WHERE column1 = P_PARAM1 AND column2 = P_PARAM2; END;", $create);
        $this->assertSame("DROP PROCEDURE IF EXISTS `Artist_customStoredProcedure`", $drop);

    }

    public function testInsertStoredProcedure()
    {
        $connection = ConnectionFactory::getInstance()->getConnection();
        $spFactory = $connection->getStoredProcedureFactory();

        $model = $this->readModelAndCreateTable(__DIR__ . "/schema/insertUpdate.test.xml");

        $artist = $model->getEntityByTableName("Artist");

        $sp = $spFactory->createInsertStoredProcedure($model, $artist);

        $drop = $sp->getDropProcedureStatement();
        $create = $sp->getCreateProcedureStatement();

        $connection->execute($drop);
        $connection->execute($create);

        // invoke the sp
        $connection->executeStoredProcedure("CALL `Artist_insert`(7, 'test', '2006-04-29 21:00:00', 123, 12.4);");


        $resultSet = $connection->query("SELECT * FROM Artist");
        $this->assertTrue($resultSet->hasNext());

        $dateTime = $resultSet->getDateTime("column2");
        $this->assertSame('2006-04-29 21:00:00', $dateTime->getSQLDateTime());
        $this->assertSame(7, $resultSet->getIntegerValue("id"));
        $this->assertSame("test", $resultSet->getStringValue("column1"));
        $this->assertSame(123, $resultSet->getIntegerValue("column3"));
        $this->assertSame(12.4, $resultSet->getFloatValue("column4"));

        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();
    }

    public function testUpdateStoredProcedure()
    {

        $this->testInsertStoredProcedure();

        $model = $this->readModelAndCreateTable(__DIR__ . "/schema/insertUpdate.test.xml");

        $artist = $model->getEntityByTableName("Artist");

        $connection = ConnectionFactory::getInstance()->getConnection();
        $spFactory = $connection->getStoredProcedureFactory();

        $sp = $spFactory->createUpdateStoredProcedure($model, $artist);

        $drop = $sp->getDropProcedureStatement();
        $create = $sp->getCreateProcedureStatement();

        $connection->execute($drop);
        $connection->execute($create);

        $connection->executeStoredProcedure("CALL `Artist_update` (7, 'test-u', '2016-04-29 21:00:00', 42, 19.08);");

        $resultSet = $connection->query("SELECT * FROM Artist");
        $this->assertTrue($resultSet->hasNext());

        $dateTime = $resultSet->getDateTime("column2");
        $this->assertSame('2016-04-29 21:00:00', $dateTime->getSQLDateTime());
        $this->assertSame(7, $resultSet->getIntegerValue("id"));
        $this->assertSame("test-u", $resultSet->getStringValue("column1"));
        $this->assertSame(42, $resultSet->getIntegerValue("column3"));
        $this->assertSame(19.08, $resultSet->getFloatValue("column4"));

        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();

    }

    public function testDeleteStoredProcedure()
    {
        $this->testInsertStoredProcedure();

        $model = $this->readModelAndCreateTable(__DIR__ . "/schema/insertUpdate.test.xml");

        $artist = $model->getEntityByTableName("Artist");

        $connection = ConnectionFactory::getInstance()->getConnection();
        $spFactory = $connection->getStoredProcedureFactory();

        $sp = $spFactory->createDeleteByPKStoredProcedure($model, $artist);

        $drop = $sp->getDropProcedureStatement();
        $create = $sp->getCreateProcedureStatement();

        $connection->execute($drop);
        $connection->execute($create);

        $connection->executeStoredProcedure("CALL `Artist_delete_by_pk` (7);");

        $resultSet = $connection->query("SELECT * FROM Artist");
        $this->assertFalse($resultSet->hasNext());

        $resultSet->close();

    }

    public function testSelectStoredProcedure()
    {
        // recreate Data
        $this->testInsertStoredProcedure();

        $connection = ConnectionFactory::getInstance()->getConnection();
        $spFactory = $connection->getStoredProcedureFactory();

        $model = $this->readModelAndCreateTable(__DIR__ . "/schema/insertUpdate.test.xml");

        $artist = $model->getEntityByTableName("Artist");

        $sp = $spFactory->createSelectByPKStoredProcedure($model, $artist);

        $drop = $sp->getDropProcedureStatement();
        $create = $sp->getCreateProcedureStatement();

        $connection->execute($drop);
        $connection->execute($create);

        $spName = sprintf(StoredProcedureNaming::SELECT_BY_PRIMARY_KEY, "Artist");

        $resultSet = $connection->executeStoredProcedure("CALL `$spName` (7)");
        $this->assertTrue($resultSet->hasNext());

        $dateTime = $resultSet->getDateTime("column2");
        $this->assertSame('2006-04-29 21:00:00', $dateTime->getSQLDateTime());
        $this->assertSame(7, $resultSet->getIntegerValue("id"));
        $this->assertSame("test", $resultSet->getStringValue("column1"));
        $this->assertSame(123, $resultSet->getIntegerValue("column3"));
        $this->assertSame(12.4, $resultSet->getFloatValue("column4"));

        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();
    }

}