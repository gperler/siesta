<?php

namespace SiestaTest\Functional\Migration;

use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class MigrateIndexTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.index.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.index.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getAlterStatementList();

        $this->assertSame(10, sizeof($alterStatementList));
        $this->assertSame("table 'migrateIndex' drop index 'uniqueChanged'", $alterStatementList[0]);
        $this->assertSame("table 'migrateIndex' drop index 'typeChanged'", $alterStatementList[1]);
        $this->assertSame("table 'migrateIndex' drop index 'columnChanged'", $alterStatementList[2]);
        $this->assertSame("table 'migrateIndex' drop index 'partChanged'", $alterStatementList[3]);

        $this->assertSame("table 'migrateIndex' drop index 'dropIndex'", $alterStatementList[4]);
        $this->assertSame("table 'migrateIndex' add index 'newIndex'", $alterStatementList[5]);
        $this->assertSame("table 'migrateIndex' add index 'uniqueChanged'", $alterStatementList[6]);
        $this->assertSame("table 'migrateIndex' add index 'typeChanged'", $alterStatementList[7]);
        $this->assertSame("table 'migrateIndex' add index 'columnChanged'", $alterStatementList[8]);
        $this->assertSame("table 'migrateIndex' add index 'partChanged'", $alterStatementList[9]);

    }

}

