<?php

namespace SiestaTest\Functional\Migration;

use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class MigrateReferenceTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.reference.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.reference.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getAlterStatementList();

        $this->assertSame(10, sizeof($alterStatementList));

        $this->assertSame("table 'Reference' drop constraint 'Reference_differentForeignTable'", $alterStatementList[0]);
        $this->assertSame("table 'Reference' drop constraint 'Reference_differentMappingCount'", $alterStatementList[1]);
        $this->assertSame("table 'Reference' drop constraint 'Reference_differentMapping'", $alterStatementList[2]);
        $this->assertSame("table 'Reference' drop constraint 'Reference_changeUpdate'", $alterStatementList[3]);
        $this->assertSame("table 'Reference' drop constraint 'Reference_deleteReference'", $alterStatementList[4]);
        $this->assertSame("table 'Reference' add reference 'Reference_addReference'", $alterStatementList[5]);
        $this->assertSame("table 'Reference' add reference 'Reference_differentForeignTable'", $alterStatementList[6]);
        $this->assertSame("table 'Reference' add reference 'Reference_differentMappingCount'", $alterStatementList[7]);
        $this->assertSame("table 'Reference' add reference 'Reference_differentMapping'", $alterStatementList[8]);
        $this->assertSame("table 'Reference' add reference 'Reference_changeUpdate'", $alterStatementList[9]);

    }

}


