<?php

namespace SiestaTest\Functional\Migration;

use Codeception\Util\Debug;
use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class MigrateStoredProcedureTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/migrate.storedprocedure.test.schema.json"));

        $dmh = new DataModelHelper();
        $datamodel = $dmh->readModel(__DIR__ . "/schema/migrate.storedprocedure.test.schema.xml");

        $migrator = new DatabaseMigrator($datamodel, $testConnection);
        $migrator->createAlterStatementList(true);

        $alterStatementList = $migrator->getCreateStoredProcedureStatementList();

        Debug::debug(json_encode($alterStatementList, JSON_PRETTY_PRINT));


    }

}