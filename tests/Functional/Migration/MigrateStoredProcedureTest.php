<?php

namespace SiestaTest\Functional\Migration;

use Codeception\Test\Unit;
use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class MigrateStoredProcedureTest extends Unit
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.storedprocedure.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.storedprocedure.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getAlterStoredProcedureStatementList();


        $this->assertCount(13, $alterStatementList);

        // new sps create statement
        $this->assertSame("create select_new_entity", $alterStatementList[0]);
        $this->assertSame("create insert_new_entity", $alterStatementList[1]);
        $this->assertSame("create update_new_entity", $alterStatementList[2]);
        $this->assertSame("create delete_new_entity", $alterStatementList[3]);

        // change sps drop and create
        $this->assertSame("create select_change_entity", $alterStatementList[4]);
        $this->assertSame("drop insert_change_entity", $alterStatementList[5]);
        $this->assertSame("create insert_change_entity", $alterStatementList[6]);
        $this->assertSame("drop update_change_entity", $alterStatementList[7]);
        $this->assertSame("create update_change_entity", $alterStatementList[8]);
        $this->assertSame("create delete_change_entity", $alterStatementList[9]);
        $this->assertSame("drop custom_change_entity_change_entity", $alterStatementList[10]);
        $this->assertSame("create custom_change_entity_change_entity", $alterStatementList[11]);

        // not needed anymore
        $this->assertSame("drop not_needed_anymore", $alterStatementList[12]);
    }

}