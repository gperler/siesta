<?php

namespace SiestaTest\Functional\Migration;

use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class MigrateAttributeTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.attribute.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.attribute.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getAlterStatementList();

        $this->assertSame(4, sizeof($alterStatementList));
        $this->assertSame("table 'migrateAttribute' add column 'addColumn'", $alterStatementList[0]);
        $this->assertSame("table 'migrateAttribute' modifiy column 'changeType'", $alterStatementList[1]);
        $this->assertSame("table 'migrateAttribute' modifiy column 'changeRequired'", $alterStatementList[2]);
        $this->assertSame("table 'migrateAttribute' drop colum 'toDelete'", $alterStatementList[3]);

    }

}
