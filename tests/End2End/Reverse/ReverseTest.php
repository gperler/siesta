<?php

namespace SiestaTest\End2End\Reverse;

use Siesta\Database\ConnectionFactory;
use Siesta\Main\Reverse;
use Siesta\Main\Siesta;
use Siesta\Migration\DatabaseMigrator;
use Siesta\Util\File;
use SiestaTest\End2End\Util\End2EndTest;

/**
 * This is actually a smoke test. A real world schema form sylius is dumped into db
 * and the reverse engineered. Important, after migrating the generated XML against
 * the db schema no changes (alter statements) should occur. Furthermore the db is
 * dropped and recreated from the actual schema.
 */
class ReverseTest extends End2EndTest
{

    public function setUp()
    {
        $this->resetSchema();
    }

    public function testReverse()
    {
        //$this->markTestSkipped();
        $reverseXML = new File(__DIR__ . "/reverse/reverse.xml");
        if ($reverseXML->exists()) {
            $reverseXML->delete();
        }

        $dbFile = new File(__DIR__ . '/schema/sylius.sql');
        $schema = $content = $dbFile->getContents();

        $connection = ConnectionFactory::getConnection();
        $connection->disableForeignKeyChecks();
        $connection->execute($schema);
        $connection->enableForeignKeyChecks();

        $reverse = new Reverse();
        $reverse->setDefaultNamespace('SiestaTest\End2End\Reverse\Generated');
        $reverse->setTargetPath('Generated');
        $reverse->createSingleXMLFile($reverseXML->getAbsoluteFileName());

        $datamodel = $this->readModel($reverseXML->getAbsoluteFileName());

        $dbMigrator = new DatabaseMigrator($datamodel, $connection);

        $this->assertSame(0, sizeof($dbMigrator->getAlterStatementList()));

    }

    public function testCreateModel()
    {
        //$this->markTestSkipped();

        $siesta = new Siesta();
        $siesta->addFile(new File(__DIR__ . "/reverse/reverse.xml"));
        $siesta->migrateDirect(__DIR__);
    }

}
