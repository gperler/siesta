<?php

namespace SiestaTest\Functional\TestTestDatabase;

use Siesta\Util\File;
use SiestaTest\TestDatabase\MetaData\TestDatabaseMetaData;

/**
 * @author Gregor Müller
 */
class TestDatabaseTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
        $testDatabase = new TestDatabaseMetaData(new File(__DIR__ . "/schema/database.test.schema.json"));

        $tableList = $testDatabase->getTableList();
        $this->assertSame(1, sizeof($tableList));

        $table = $tableList[0];
        $this->assertSame("TestTable", $table->getName());
        $table = $testDatabase->getTableByName("TestTable");
        $this->assertNotNull($table);


        $columnList = $table->getColumnList();
        $this->assertSame(1, sizeof($columnList));
        $column = $table->getColumnByName("dbName");
        $this->assertNotNull($column);

        $this->assertSame("dbName", $column->getDBName());
        $this->assertSame("dbType", $column->getDBType());
        $this->assertSame("phpType", $column->getPHPType());
        $this->assertSame(true, $column->getIsRequired());
        $this->assertSame(true, $column->getIsPrimaryKey());



    }

}