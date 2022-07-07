<?php

namespace SiestaTest\End2End\CustomServiceClass;

use Siesta\Util\File;
use SiestaTest\End2End\Util\End2EndTest;

class CustomServiceClassTest extends End2EndTest
{

    public function setUp(): void
    {
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/custom.service.test.xml");
        $this->generateSchema($schemaFile, __DIR__, true);
    }

    public function testConstructEntity()
    {

    }

}
