<?php

namespace SiestaTest\Functional\Migration;

use Codeception\Test\Unit;
use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestDatabase\TestCreateStatementFactory;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class MigrateDatabaseTest extends Unit
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.database.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.database.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getAlterStatementList();

        $this->assertSame(3, sizeof($alterStatementList));
        $this->assertSame(TestCreateStatementFactory::SEQUENCER_TABLE_CREATE, $alterStatementList[0]);
        $this->assertSame("create table 'AddTable'", $alterStatementList[1]);
        $this->assertSame("drop table 'ToDelete'", $alterStatementList[2]);
    }

}