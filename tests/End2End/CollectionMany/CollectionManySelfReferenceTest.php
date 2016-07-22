<?php

namespace SiestaTest\End2End\Collection;

use Siesta\Util\File;
use SiestaTest\End2End\CollectionMany\Generated\Exam;
use SiestaTest\End2End\CollectionMany\Generated\ExamService;
use SiestaTest\End2End\CollectionMany\Generated\ExamUUID;
use SiestaTest\End2End\CollectionMany\Generated\ExamUUIDService;
use SiestaTest\End2End\CollectionMany\Generated\Product;
use SiestaTest\End2End\CollectionMany\Generated\Student;
use SiestaTest\End2End\CollectionMany\Generated\StudentService;
use SiestaTest\End2End\CollectionMany\Generated\StudentUUID;
use SiestaTest\End2End\CollectionMany\Generated\StudentUUIDService;
use SiestaTest\End2End\Util\End2EndTest;

class CollectionManySelfReferenceTest extends End2EndTest
{

    public function setUp()
    {
        $silent = false;
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/collection.many.self.reference.test.xml");
        $this->generateSchema($schemaFile, __DIR__, $silent);

    }

    public function testCollectionMany()
    {

        $sessions = new Product();
        $sessions->setName("Kruder & Dorfmeister K&D Sessions");

        $stGermain = new Product();
        $stGermain->setName("St Germain Boulevard");

        $mezzanine = new Product();
        $mezzanine->setName("Massive Attack Mezzanine");

        $sessions->addToRelatedProductList($mezzanine);
        $sessions->addToRelatedProductList($stGermain);

        $sessions->save(true);


    }



}