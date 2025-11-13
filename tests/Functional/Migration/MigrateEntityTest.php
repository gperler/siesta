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
class MigrateEntityTest extends Unit
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.entity.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.entity.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getAlterStatementList();
        $this->assertSame(9, sizeof($alterStatementList));

        // the order of the alter statements is important
        $this->assertSame(TestCreateStatementFactory::SEQUENCER_TABLE_CREATE, $alterStatementList[0]);
        $this->assertSame("table 'migrateEntity1' drop constraint 'migrateEntity1_dropConstraint'", $alterStatementList[1]);
        $this->assertSame("table 'migrateEntity1' add column 'toAdd'", $alterStatementList[2]);
        $this->assertSame("table 'migrateEntity1' modify pk", $alterStatementList[3]);
        $this->assertSame("table 'migrateEntity1' drop colum '1-primary2'", $alterStatementList[4]);
        $this->assertSame("table 'migrateEntity1' drop colum '1-foreign'", $alterStatementList[5]);
        $this->assertSame("table 'migrateEntity1' add reference 'migrateEntity1_referenceAdd'", $alterStatementList[6]);
        $this->assertSame("table 'migrateEntity2' add column '2-primary2'", $alterStatementList[7]);
        $this->assertSame("table 'migrateEntity2' modify pk", $alterStatementList[8]);

    }

}


