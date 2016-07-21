<?php

namespace SiestaTest\Functional\MySQL\CreateTable;

use Codeception\Util\Debug;
use Siesta\Config\Config;
use Siesta\Database\ConnectionFactory;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Util\File;
use Siesta\XML\XMLReader;
use SiestaTest\TestUtil\DataModelHelper;

class CreateTableTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    protected function readSchema(string $fileName)
    {
        $xmlReader = new XMLReader(new File(__DIR__ . $fileName));
        $xmlEntityList = $xmlReader->getEntityList();
        $xmlEntity = $xmlEntityList[0];

        $entity = new Entity(new DataModel());
        $entity->fromXMLEntity($xmlEntity);
        return $entity;
    }

    public function testAttributeCreate()
    {
        $dmr = new DataModelHelper();

        $validationLogger = $dmr->getValidationLogger(true);
        $validatior = $dmr->getValidator(false);
        $model = $dmr->readModel(__DIR__ . "/schema/create.table.attribute.test.xml");

        $validatior->validateDataModel($model, $validationLogger);
        $this->assertFalse($validationLogger->hasError());

        $connection = ConnectionFactory::getConnection();
        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);

        $queryActualList = $factory->buildCreateTable($model->getEntityByTableName("Artist"));
        $this->assertSame(1, sizeof($queryActualList));
        $expected = "CREATE TABLE IF NOT EXISTS `Artist` (`id` INT NOT NULL, `smallint` SMALLINT NULL, `int` INT NOT NULL, `string` VARCHAR(100) NULL, `datetime` DATETIME NULL, `date` DATE NULL, `time` TIME NULL, PRIMARY KEY (`id`,`string`))";
        $this->assertSame($expected, $queryActualList[0]);
        $connection->execute($queryActualList[0]);
    }
    
    
    public function testReferenceCreate() {
        $dmr = new DataModelHelper();

        $validationLogger = $dmr->getValidationLogger(true);
        $validatior = $dmr->getValidator(false);
        $model = $dmr->readModel(__DIR__ . "/schema/create.table.reference.test.xml");

        $validatior->validateDataModel($model, $validationLogger);
        $this->assertFalse($validationLogger->hasError());

        $connection = ConnectionFactory::getConnection();
        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);

        $queryActualList = $factory->buildCreateTable($model->getEntityByTableName("Artist"));
        $expected = "CREATE TABLE IF NOT EXISTS `Artist` (`id` INT NOT NULL, `name` VARCHAR(36) NOT NULL, PRIMARY KEY (`id`))";
        $this->assertSame($expected, $queryActualList[0]);
        $connection->execute($queryActualList[0]);

        $queryActualList = $factory->buildCreateTable($model->getEntityByTableName("CD"));
        $expected = "CREATE TABLE IF NOT EXISTS `CD` (`id` VARCHAR(36) NULL, `name` VARCHAR(36) NOT NULL, `fk_artist` INT NULL, PRIMARY KEY (`id`), CONSTRAINT `CD_artist` FOREIGN KEY (`fk_artist`) REFERENCES `Artist` (`id`) ON DELETE SET NULL ON UPDATE CASCADE)";
        $this->assertSame($expected, $queryActualList[0]);
        $connection->execute($queryActualList[0]);

        $queryActualList = $factory->buildCreateTable($model->getEntityByTableName("Owner"));
        $expected = "CREATE TABLE IF NOT EXISTS `Owner` (`a` VARCHAR(36) NULL, `b` VARCHAR(36) NULL, PRIMARY KEY (`a`,`b`))";
        $this->assertSame($expected, $queryActualList[0]);
        $connection->execute($queryActualList[0]);

        $queryActualList = $factory->buildCreateTable($model->getEntityByTableName("Label"));
        $expected = "CREATE TABLE IF NOT EXISTS `Label` (`id` VARCHAR(36) NULL, `fk_a` VARCHAR(36) NULL, `fk_b` VARCHAR(36) NULL, PRIMARY KEY (`id`), CONSTRAINT `Label_owner` FOREIGN KEY (`fk_a`,`fk_b`) REFERENCES `Owner` (`a`,`b`) ON DELETE CASCADE ON UPDATE NO ACTION)";
        $this->assertSame($expected, $queryActualList[0]);
        $connection->execute($queryActualList[0]);
    }
    
    
    public function testCreateIndex() {
        $dmr = new DataModelHelper();

        $validationLogger = $dmr->getValidationLogger(true);
        $validatior = $dmr->getValidator(true);
        $model = $dmr->readModel(__DIR__ . "/schema/create.table.index.test.xml");

        $validatior->validateDataModel($model, $validationLogger);
        $this->assertFalse($validationLogger->hasError());

        $connection = ConnectionFactory::getConnection();
        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);

        $queryActualList = $factory->buildCreateTable($model->getEntityByTableName("IndexTest"));
        $expected = "CREATE TABLE IF NOT EXISTS `IndexTest` (`id` INT NOT NULL, `smallint` SMALLINT NULL, `int` INT NOT NULL, `string` VARCHAR(100) NULL, `datetime` DATETIME NULL, `date` DATE NULL, `time` TIME NULL, PRIMARY KEY (`id`,`string`), UNIQUE INDEX `index1` USING btree (`string` (10) ASC), INDEX `index2` USING btree (`datetime` ASC, `date` ASC))";
        $this->assertSame($expected, $queryActualList[0]);
        $connection->execute($queryActualList[0]);

    }

}