<?php

namespace SiestaTest\Functional\MySQL\MetaData;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;
use Siesta\Util\ArrayUtil;
use Siesta\Util\File;
use SiestaTest\TestUtil\DataModelHelper;

class AttributeMetaDataTest extends Unit
{

    protected function setUp(): void
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }


    public function testAttributeMetaData()
    {
        $dmr = new DataModelHelper();
        $dmr->createSchema(__DIR__ . "/schema/attribute.test.xml", false);

        $connection = ConnectionFactory::getConnection();

        $databaseMeta = $connection->getDatabaseMetaData();
        $tableMetaDataList = $databaseMeta->getTableList();
        $this->assertSame(1, sizeof($tableMetaDataList));

        $tableMeta = $databaseMeta->getTableByName("Attribute");
        $this->assertNotNull($tableMeta);

        $columnMetaList = $tableMeta->getColumnList();

        $this->assertSame(30, sizeof($columnMetaList));

        $expectedFile = new File(__DIR__ . "/schema/attribute.test.expected.json");
        $expectedList = $expectedFile->loadAsJSONArray();
        $this->assertNotNull($expectedList);
        $this->assertTrue(is_array($expectedList));

        foreach ($columnMetaList as $column) {
            $columnName = $column->getDBName();

            $expectedData = ArrayUtil::getFromArray($expectedList, $columnName);
            $this->assertNotNull($expectedData);
            $this->assertSame($expectedData["dbType"], $column->getDBType());
            $this->assertSame($expectedData["phpType"], $column->getPHPType());
            $this->assertSame($expectedData["required"], $column->getIsRequired());
            $this->assertSame($expectedData["primaryKey"], $column->getIsPrimaryKey());
        }
    }

}