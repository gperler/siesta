<?php

namespace SiestaTest\Functional\TestTestDatabase;

use Codeception\Test\Unit;
use Siesta\Util\File;
use SiestaTest\TestDatabase\MetaData\TestDatabaseMetaData;

/**
 * @author Gregor Müller
 */
class TestDatabaseTest extends Unit
{

    /**
     * @throws \SiestaTest\TestUtil\TestException
     */
    public function testDatabase()
    {
        $testDatabase = new TestDatabaseMetaData(new File(__DIR__ . "/schema/database.test.schema.json"));

        $tableList = $testDatabase->getTableList();
        $this->assertSame(1, sizeof($tableList));

        $table = $tableList["TestTable"];
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


        //
        //

        $spList = $testDatabase->getStoredProcedureList();
        $this->assertNotNull($spList);
        $this->assertCount(2, $spList);

        $sp1 = $spList[0];
        $this->assertSame("sp1", $sp1->getProcedureName());
        $this->assertSame("create sp1", $sp1->getCreateProcedureStatement());
        $this->assertSame("drop sp1", $sp1->getDropProcedureStatement());


    }

}