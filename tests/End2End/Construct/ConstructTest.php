<?php

namespace SiestaTest\End2End\Construct;


use Siesta\Util\File;
use SiestaTest\End2End\Attribute\Generated\E2EAttribute;
use SiestaTest\End2End\Construct\Generated\ConstructEntityService;
use SiestaTest\End2End\Construct\Generated\FactoryEntityService;
use SiestaTest\End2End\Util\End2EndTest;

class ConstructTest extends End2EndTest
{

    public function setUp(): void
    {
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/construct1.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    public function testConstructEntity()
    {
        $service = ConstructEntityService::getInstance();
        $this->assertNotNull($service);

        $instance = $service->newInstance();
        $this->assertNotNull($instance);

        $this->assertInstanceOf('SiestaTest\End2End\Construct\Entity\Construct', $instance);

    }


    public function testConstructFactory() {

        $service = FactoryEntityService::getInstance();
        $this->assertNotNull($service);

        $instance = $service->newInstance();
        $this->assertNotNull($instance);


        $this->assertInstanceOf('SiestaTest\End2End\Construct\Entity\Factory', $instance);
        $this->assertSame("FactoryGeneratedEntity", $instance->getString1());

    }
    
}
