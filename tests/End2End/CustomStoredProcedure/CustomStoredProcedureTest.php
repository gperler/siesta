<?php

namespace SiestaTest\End2End\CustomStoredProcedure;

use Siesta\Util\File;
use SiestaTest\End2End\CustomStoredProcedure\Generated\E2ECustomStoredProcedure;
use SiestaTest\End2End\CustomStoredProcedure\Generated\E2ECustomStoredProcedureService;
use SiestaTest\End2End\Util\End2EndTest;

class CustomStoredProcedureTest extends End2EndTest
{

    public function setUp(): void
    {
        //$this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/custom.stored.procedure.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    /**
     *
     */
    public function testSingleResult()
    {
        $entity1 = new E2ECustomStoredProcedure();
        $entity1->save();
        $entity2 = new E2ECustomStoredProcedure();
        $entity2->save();
        $entity3 = new E2ECustomStoredProcedure();
        $entity3->save();

        $service = new E2ECustomStoredProcedureService();
        $entity = $service->getSingle('Discovery', 42);

        $this->assertNotNull($entity);
        $this->assertSame(1, $entity->getId());

    }

    /**
     *
     */
    public function testListResult()
    {
        $entity1 = new E2ECustomStoredProcedure();
        $entity1->save();
        $entity2 = new E2ECustomStoredProcedure();
        $entity2->save();
        $entity3 = new E2ECustomStoredProcedure();
        $entity3->save();

        $service = new E2ECustomStoredProcedureService();
        $entityList = $service->getList('Discovery', 42);

        $this->assertSame(3, sizeof($entityList));
        $this->assertSame(1, $entityList[0]->getId());
        $this->assertSame(2, $entityList[1]->getId());
        $this->assertSame(3, $entityList[2]->getId());

    }

    public function testResultSetResult()
    {
        $entity1 = new E2ECustomStoredProcedure();
        $entity1->save();
        $entity2 = new E2ECustomStoredProcedure();
        $entity2->save();
        $entity3 = new E2ECustomStoredProcedure();
        $entity3->save();

        $service = new E2ECustomStoredProcedureService();
        $resultSet = $service->getResultSet('Discovery', 42);

        $this->assertTrue($resultSet->hasNext());
        $this->assertSame(1, $resultSet->getIntegerValue("id"));

        $this->assertTrue($resultSet->hasNext());
        $this->assertSame(2, $resultSet->getIntegerValue("id"));

        $this->assertTrue($resultSet->hasNext());
        $this->assertSame(3, $resultSet->getIntegerValue("id"));

        $this->assertFalse($resultSet->hasNext());

        $resultSet->close();

        $resultSet = $service->getResultSet('Discovery', 42);
        while ($resultSet->hasNext()) {
            $boolValue = $resultSet->getBooleanValue(E2ECustomStoredProcedure::COLUMN_BOOL);
            $intValue = $resultSet->getIntegerValue(E2ECustomStoredProcedure::COLUMN_INT);
            $floatValue = $resultSet->getIntegerValue(E2ECustomStoredProcedure::COLUMN_FLOAT);
            $stringValue = $resultSet->getStringValue(E2ECustomStoredProcedure::COLUMN_STRING);
            $dateValue = $resultSet->getDateTime(E2ECustomStoredProcedure::COLUMN_DATETIME);
            $arrayValue = $resultSet->getArray(E2ECustomStoredProcedure::COLUMN_OBJECT);

            $objectValue = $resultSet->getArray(E2ECustomStoredProcedure::COLUMN_OBJECT);
        }
        $resultSet->close();

    }

    public function testNoResult()
    {
        $entity1 = new E2ECustomStoredProcedure();
        $entity1->save();
        $entity2 = new E2ECustomStoredProcedure();
        $entity2->save();
        $entity3 = new E2ECustomStoredProcedure();
        $entity3->save();

        $service = new E2ECustomStoredProcedureService();
        $service->updateTable("Daft Punk", 2001);

        $entityList = $service->getList('Daft Punk', 2001);

        $this->assertSame(3, sizeof($entityList));
        $this->assertSame(1, $entityList[0]->getId());
        $this->assertSame(2, $entityList[1]->getId());
        $this->assertSame(3, $entityList[2]->getId());

    }

}
