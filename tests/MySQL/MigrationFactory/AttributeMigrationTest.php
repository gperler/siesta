<?php

namespace SiestaTest\Functional\MySQL\MigrationFactory;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Driver\MySQL\MetaData\MySQLColumn;
use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use SiestaTest\TestUtil\DataModelHelper;

class AttributeMigrationTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    public function testAttributeMigration()
    {

        $dmr = new DataModelHelper();
        $dmr->createSchema(__DIR__ . "/schema/attribute.test.xml", true);

        $connection = ConnectionFactory::getConnection();

        $factory = $connection->getMigrationStatementFactory();

        // test add column
        $attribute = new Attribute(new Entity(new DataModel()));
        $attribute->setDbType("VARCHAR(100)");
        $attribute->setDbName("addedAttribute");
        $attribute->setIsRequired(true);

        $statementList = $factory->createAddColumnStatement($attribute);
        $statement = $this->postProcessStatement($statementList, "Attribute");
        $this->assertSame("ALTER TABLE `Attribute` ADD `addedAttribute` VARCHAR(100) NOT NULL", $statement);

        $attribute->setIsRequired(false);
        $statementList = $factory->createAddColumnStatement($attribute);
        $statement = $this->postProcessStatement($statementList, "Attribute");
        $this->assertSame("ALTER TABLE `Attribute` ADD `addedAttribute` VARCHAR(100) NULL", $statement);
        $connection->execute($statement);

        $attribute->setDbName("modifyAttribute");
        $attribute->setDbType("INT");
        $attribute->setIsRequired(true);
        $statementList = $factory->createModifiyColumnStatement($attribute);
        $statement = $this->postProcessStatement($statementList, "Attribute");
        $this->assertSame("ALTER TABLE `Attribute` MODIFY `modifyAttribute` INT NOT NULL", $statement);
        $connection->execute($statement);

        //
        $column = new MySQLColumn();
        $column->setDBName("time");
        $statementList = $factory->createDropColumnStatement($column);
        $statement = $this->postProcessStatement($statementList, "Attribute");
        $this->assertSame("ALTER TABLE `Attribute` DROP COLUMN `time`", $statement);
        $connection->execute($statement);

        $metaData = $connection->getDatabaseMetaData();

        $tableMeta = $metaData->getTableByName("Attribute");
        $this->assertNotNull($tableMeta);

        $column = $tableMeta->getColumnByName("addedAttribute");
        $this->assertNotNull($column);
        $this->assertSame(false, $column->getIsRequired());
        $this->assertSame("VARCHAR(100)", $column->getDBType());

        $column = $tableMeta->getColumnByName("modifyAttribute");
        $this->assertNotNull($column);
        $this->assertSame(true, $column->getIsRequired());
        $this->assertSame("INT", $column->getDBType());

        $column = $tableMeta->getColumnByName("time");
        $this->assertNull($column);
    }

    /**
     * @param array $statementList
     * @param string $tableName
     *
     * @return string
     */
    public function postProcessStatement(array $statementList, string $tableName) : string
    {
        return str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $tableName, $statementList[0]);
    }

}