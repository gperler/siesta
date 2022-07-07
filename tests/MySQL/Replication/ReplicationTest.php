<?php

namespace SiestaTest\MySQL\Replication;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Util\File;
use Siesta\Util\SiestaDateTime;
use SiestaTest\MySQL\Replication\Generated\MySQLReplication;
use SiestaTest\MySQL\Util\MySQLTest;

class ReplicationTest extends MySQLTest
{

    public function setUp(): void
    {
        //$this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/replication.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    public function testSave()
    {
        $replication = new MySQLReplication();
        $replication->setBool(true);
        $replication->setInt(9201);
        $replication->setFloat(312.231);
        $replication->setString("teststring");
        $replication->setDateTime(new SiestaDateTime("2001-07-06 20:00:00"));
        $replication->setDate(new SiestaDateTime("2077-08-19"));
        $replication->setTime(new SiestaDateTime("18:15:00"));
        $replication->getObject()->setX(9);
        $replication->getObject()->setY(12);
        $replication->save();

        $replication->setString("test-2");
        $replication->save();

        $replication2 = new MySQLReplication();
        $replication2->save();
        $replication2->delete();

        $connection = $this->getConnection();

        $spSelect = sprintf(StoredProcedureNaming::SELECT_BY_PRIMARY_KEY, "MySQLReplication");

        $sql = "CALL " . $spSelect . " ( " . $replication->getId() . ")";

        $resultSet = $connection->executeStoredProcedure($sql);

        while ($resultSet->hasNext()) {
        }
        $resultSet->close();

    }

}
