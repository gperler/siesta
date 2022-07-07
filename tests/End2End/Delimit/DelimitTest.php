<?php

namespace SiestaTest\End2End\Delimit;

use Codeception\Util\Debug;
use Siesta\Model\DelimitAttributeList;
use Siesta\Model\Entity;
use Siesta\Util\File;
use Siesta\Util\SiestaDateTime;
use SiestaTest\End2End\Delimit\Generated\E2EDelimit;
use SiestaTest\End2End\Util\End2EndTest;

class DelimitTest extends End2EndTest
{

    public function setUp(): void
    {
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/delimit.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    public function testSave()
    {

        $delimit = new E2EDelimit();
        $delimit->setBool(true);
        $delimit->setInt(9201);
        $delimit->setFloat(312.231);
        $delimit->setString("teststring");
        $delimit->setDateTime(new SiestaDateTime("2001-07-06 20:00:00"));
        $delimit->setDate(new SiestaDateTime("2077-08-19"));
        $delimit->setTime(new SiestaDateTime("18:15:00"));
        $delimit->getObject()->setX(9);
        $delimit->getObject()->setY(12);
        $delimit->save();

        $delimit->setBool(false);
        $delimit->setInt(9202);
        $delimit->setFloat(312.232);
        $delimit->setString("teststring-2");
        $delimit->setDateTime(new SiestaDateTime("2001-07-06 20:00:02"));
        $delimit->setDate(new SiestaDateTime("2077-08-20"));
        $delimit->setTime(new SiestaDateTime("18:15:01"));
        $delimit->getObject()->setX(10);
        $delimit->getObject()->setY(11);
        $delimit->save();


        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EDelimit" . Entity::DELIMIT_SUFFIX);

        // test version 1 is there
        $this->assertTrue($resultSet->hasNext());
        $version1 = new E2EDelimit();
        $version1->fromResultSet($resultSet);
        $this->assertSame(true, $version1->getBool());
        $this->assertSame(9201, $version1->getInt());
        $this->assertSame(312.231, $version1->getFloat());
        $this->assertSame("teststring", $version1->getString());
        $this->assertSame("2001-07-06 20:00:00", $version1->getDateTime()->getSQLDateTime());
        $this->assertSame("2077-08-19", $version1->getDate()->getSQLDate());
        $this->assertSame("18:15:00", $version1->getTime()->getSQLTime());
        $this->assertSame(9, $version1->getObject()->getX());
        $this->assertSame(12, $version1->getObject()->getY());

        $this->assertNotNull($version1->getAdditionalColumn(DelimitAttributeList::COLUMN_VALID_FROM));
        $this->assertNotNull($version1->getAdditionalColumn(DelimitAttributeList::COLUMN_DELIMIT_ID));
        $this->assertNotNull($version1->getAdditionalColumn(DelimitAttributeList::COLUMN_VALID_UNTIL));

        // test version 2 is there
        $this->assertTrue($resultSet->hasNext());
        $version2 = new E2EDelimit();
        $version2->fromResultSet($resultSet);
        $this->assertSame(false, $version2->getBool());
        $this->assertSame(9202, $version2->getInt());
        $this->assertSame(312.232, $version2->getFloat());
        $this->assertSame("teststring-2", $version2->getString());
        $this->assertSame("2001-07-06 20:00:02", $version2->getDateTime()->getSQLDateTime());
        $this->assertSame("2077-08-20", $version2->getDate()->getSQLDate());
        $this->assertSame("18:15:01", $version2->getTime()->getSQLTime());
        $this->assertSame(10, $version2->getObject()->getX());
        $this->assertSame(11, $version2->getObject()->getY());
        $this->assertNotNull($version2->getAdditionalColumn(DelimitAttributeList::COLUMN_VALID_FROM));
        $this->assertNotNull($version2->getAdditionalColumn(DelimitAttributeList::COLUMN_DELIMIT_ID));
        $this->assertNull($version2->getAdditionalColumn(DelimitAttributeList::COLUMN_VALID_UNTIL));

        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();

        // delete the delimited entity
        $delimit->delete();

        // check column is deleted from actual table
        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EDelimit");
        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();


        // check 
        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EDelimit" . Entity::DELIMIT_SUFFIX . " WHERE " . DelimitAttributeList::COLUMN_VALID_UNTIL . " IS NULL");

        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();


    }

}
