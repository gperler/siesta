<?php

namespace SiestaTest\End2End\Collection;

use Codeception\Util\Debug;
use Siesta\Util\File;
use SiestaTest\End2End\CollectionMany\Generated\Exam;
use SiestaTest\End2End\CollectionMany\Generated\ExamService;
use SiestaTest\End2End\CollectionMany\Generated\ExamUUID;
use SiestaTest\End2End\CollectionMany\Generated\ExamUUIDService;
use SiestaTest\End2End\CollectionMany\Generated\Product;
use SiestaTest\End2End\CollectionMany\Generated\ProductService;
use SiestaTest\End2End\CollectionMany\Generated\ProductUUID;
use SiestaTest\End2End\CollectionMany\Generated\ProductUUIDService;
use SiestaTest\End2End\CollectionMany\Generated\Student;
use SiestaTest\End2End\CollectionMany\Generated\StudentService;
use SiestaTest\End2End\CollectionMany\Generated\StudentUUID;
use SiestaTest\End2End\CollectionMany\Generated\StudentUUIDService;
use SiestaTest\End2End\Util\End2EndTest;

class CollectionManySelfReferenceTest extends End2EndTest
{

    public function setUp(): void
    {
        $silent = true;
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/collection.many.self.reference.test.xml");
        $this->generateSchema($schemaFile, __DIR__, $silent);

    }

    public function testCollectionMany()
    {

        $productService = ProductService::getInstance();


        $sessions = new Product();
        $sessions->setName("Kruder & Dorfmeister K&D Sessions");

        $stGermain = new Product();
        $stGermain->setName("St Germain Boulevard");

        $mezzanine = new Product();
        $mezzanine->setName("Massive Attack Mezzanine");

        $sessions->addToRelatedProductList($mezzanine);
        $sessions->addToRelatedProductList($stGermain);

        $sessions->save(true);

        // reload and check assignment exists
        $relatedProductList = $sessions->getRelatedProductList(true);
        $this->assertSame(2, sizeof($relatedProductList));

        // delete a specific assignment
        $sessions->deleteFromRelatedProductList($stGermain->getId());
        $relatedProductList = $sessions->getRelatedProductList();
        $this->assertSame(1, sizeof($relatedProductList));
        $this->assertSame($mezzanine->getId(), $relatedProductList[0]->getId());

        // test with reload
        $relatedProductList = $sessions->getRelatedProductList(true);
        $this->assertSame(1, sizeof($relatedProductList));
        $this->assertSame($mezzanine->getId(), $relatedProductList[0]->getId());

        // delete all assignment
        $sessions->deleteFromRelatedProductList();
        $relatedProductList = $sessions->getRelatedProductList();
        $this->assertSame(0, sizeof($relatedProductList));

        $relatedProductList = $sessions->getRelatedProductList(true);
        $this->assertSame(0, sizeof($relatedProductList));

        // reload sessions
        $sessionsReloaded = $productService->getEntityByPrimaryKey($sessions->getId());
        $sessionsReloaded->addToRelatedProductList($stGermain);
        $sessionsReloaded->addToRelatedProductList($mezzanine);
        $sessionsReloaded->save(true);

        // delete specific referenced entity
        $sessionsReloaded->deleteAssignedProduct($stGermain->getId());
        $relatedProductList = $sessionsReloaded->getRelatedProductList();
        $this->assertSame(1, sizeof($relatedProductList));
        $this->assertSame($mezzanine->getName(), $relatedProductList[0]->getName());


        $deletedEntity = $productService->getEntityByPrimaryKey($stGermain->getId());
        $this->assertNull($deletedEntity);

        // delete all referenced entity
        $sessionsReloaded->deleteAssignedProduct();

        $relatedProductList = $sessionsReloaded->getRelatedProductList();
        $this->assertSame(0, sizeof($relatedProductList));
        $deletedEntity = $productService->getEntityByPrimaryKey($mezzanine->getId());
        $this->assertNull($deletedEntity);
    }


    public function testCollectionManyUUID()
    {

        $productService = ProductUUIDService::getInstance();


        $sessions = new ProductUUID();
        $sessions->setName("Kruder & Dorfmeister K&D Sessions");

        $stGermain = new ProductUUID();
        $stGermain->setName("St Germain Boulevard");

        $mezzanine = new ProductUUID();
        $mezzanine->setName("Massive Attack Mezzanine");

        $sessions->addToRelatedProductList($mezzanine);
        $sessions->addToRelatedProductList($stGermain);

        $sessions->save(true);

        // reload and check assignment exists
        $relatedProductList = $sessions->getRelatedProductList(true);
        $this->assertSame(2, sizeof($relatedProductList));

        // delete a specific assignment
        $sessions->deleteFromRelatedProductList($stGermain->getId());
        $relatedProductList = $sessions->getRelatedProductList();
        $this->assertSame(1, sizeof($relatedProductList));
        $this->assertSame($mezzanine->getId(), $relatedProductList[0]->getId());

        // test with reload
        $relatedProductList = $sessions->getRelatedProductList(true);
        $this->assertSame(1, sizeof($relatedProductList));
        $this->assertSame($mezzanine->getId(), $relatedProductList[0]->getId());

        // delete all assignment
        $sessions->deleteFromRelatedProductList();
        $relatedProductList = $sessions->getRelatedProductList();
        $this->assertSame(0, sizeof($relatedProductList));

        $relatedProductList = $sessions->getRelatedProductList(true);
        $this->assertSame(0, sizeof($relatedProductList));

        // reload sessions
        $sessionsReloaded = $productService->getEntityByPrimaryKey($sessions->getId());
        $sessionsReloaded->addToRelatedProductList($stGermain);
        $sessionsReloaded->addToRelatedProductList($mezzanine);
        $sessionsReloaded->save(true);

        // delete specific referenced entity
        $sessionsReloaded->deleteAssignedProductUUID($stGermain->getId());
        $relatedProductList = $sessionsReloaded->getRelatedProductList();
        $this->assertSame(1, sizeof($relatedProductList));
        $this->assertSame($mezzanine->getName(), $relatedProductList[0]->getName());


        $deletedEntity = $productService->getEntityByPrimaryKey($stGermain->getId());
        $this->assertNull($deletedEntity);

        // delete all referenced entity
        $sessionsReloaded->deleteAssignedProductUUID();

        $relatedProductList = $sessionsReloaded->getRelatedProductList();
        $this->assertSame(0, sizeof($relatedProductList));
        $deletedEntity = $productService->getEntityByPrimaryKey($mezzanine->getId());
        $this->assertNull($deletedEntity);
    }

}