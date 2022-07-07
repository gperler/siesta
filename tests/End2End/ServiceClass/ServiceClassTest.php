<?php

namespace SiestaTest\End2End\ServiceClass;

use Siesta\Util\File;
use SiestaTest\End2End\ServiceClass\Generated\E2EServiceClass;
use SiestaTest\End2End\ServiceClass\Generated\E2EServiceClassService;
use SiestaTest\End2End\Util\End2EndTest;

class ServiceClassTest extends End2EndTest
{

    public function setUp(): void
    {
        //$this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/service.class.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    /**
     *
     */
    public function testSingleton()
    {
        $instance = E2EServiceClassService::getInstance();
        $this->assertNotNull($instance);
        $this->assertInstanceOf('SiestaTest\End2End\ServiceClass\Generated\E2EServiceClassService', $instance);

        $direct = new E2EServiceClassService();
        $this->assertNotNull($direct);
        $this->assertInstanceOf('SiestaTest\End2End\ServiceClass\Generated\E2EServiceClassService', $direct);

    }

    public function testGetEntityById()
    {
        $e2eService = new E2EServiceClass();
        $e2eService->save();

        $this->assertNotNull($e2eService->getId());

        $service = E2EServiceClassService::getInstance();
        $reloaded = $service->getEntityByPrimaryKey($e2eService->getId());

        $this->assertNotNull($reloaded);
        $this->assertSame($e2eService->getId(), $reloaded->getId());
    }

    public function testDeleteEntityById()
    {
        $e2eService = new E2EServiceClass();
        $e2eService->save();

        $service = E2EServiceClassService::getInstance();
        $service->deleteEntityByPrimaryKey($e2eService->getId());

        $reloaded = $service->getEntityByPrimaryKey($e2eService->getId());
        $this->assertNull($reloaded);

    }

    public function testBatchSave()
    {
        $batchList = [
            new E2EServiceClass(),
            new E2EServiceClass(),
            new E2EServiceClass(),
            new E2EServiceClass()
        ];

        $service = E2EServiceClassService::getInstance();
        $service->batchSave($batchList);

        $entity0 = $service->getEntityByPrimaryKey($batchList[0]->getId());
        $this->assertNotNull($entity0);
        $entity1 = $service->getEntityByPrimaryKey($batchList[1]->getId());
        $this->assertNotNull($entity1);
        $entity2 = $service->getEntityByPrimaryKey($batchList[2]->getId());
        $this->assertNotNull($entity2);
        $entity3 = $service->getEntityByPrimaryKey($batchList[3]->getId());
        $this->assertNotNull($entity3);

    }

}
