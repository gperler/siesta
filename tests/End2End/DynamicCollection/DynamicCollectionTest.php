<?php

namespace SiestaTest\End2End\DynamicCollection;

use Siesta\Util\File;
use Siesta\Util\NoCycleDetector;
use SiestaTest\End2End\DynamicCollection\Generated\Document;
use SiestaTest\End2End\DynamicCollection\Generated\OtherTable;
use SiestaTest\End2End\DynamicCollection\Generated\OtherTableService;
use SiestaTest\End2End\Util\End2EndTest;

class DynamicCollectionTest extends End2EndTest
{

    public function setUp(): void
    {
        $silent = true;
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/dynamic.test.xml");
        $this->generateSchema($schemaFile, __DIR__, $silent);
    }

    public function tearDown(): void
    {
    }

    /**
     *
     */
    public function testSaveLoad()
    {
        $otherTable = new OtherTable();

        $document = new Document();
        $document->setName("d1");
        $otherTable->addToDocumentList($document);

        $document = new Document();
        $document->setName("d2");
        $otherTable->addToDocumentList($document);

        $document2 = new Document();
        $document2->setName("d3");
        $otherTable->addToDocumentList2($document2);

        $otherTable->save(true);

        $tableReload = OtherTableService::getInstance()->getEntityByPrimaryKey($otherTable->getId());
        $this->assertNotNull($tableReload);

        $documentList = $tableReload->getDocumentList();
        $this->assertCount(2, $documentList);

        foreach ($documentList as $index => $document) {
            $this->assertSame("d" . ($index + 1), $document->getName());
        }

        $documentList2 = $tableReload->getDocumentList2();
        $this->assertCount(1, $documentList2);
        $this->assertSame("d3", $documentList2[0]->getName());

    }

    public function testToArrayConversion()
    {
        $otherTable = new OtherTable();

        $document = new Document();
        $document->setName("d1");
        $otherTable->addToDocumentList($document);

        $document = new Document();
        $document->setName("d2");
        $otherTable->addToDocumentList($document);

        $document = new Document();
        $document->setName("d3");
        $otherTable->addToDocumentList2($document);

        $array = $otherTable->toArray(new NoCycleDetector());

        $id = $array["id"];
        $this->assertNotNull($id);

        $documentList = $array["documentList"];
        $this->assertNotNull($documentList);
        $this->assertCount(2, $documentList);
        foreach ($documentList as $index => $document) {
            $this->assertSame("d" . ($index + 1), $document["name"]);
            $this->assertSame(OtherTable::TABLE_NAME, $document["_foreignTable"]);
            $this->assertSame("documentList", $document["_foreignName"]);
            $this->assertSame($id, $document["_foreignId"]);
        }

        $documentList2 = $array["documentList2"];
        $this->assertNotNull($documentList2);
        $this->assertCount(1, $documentList2);
        $document = $documentList2[0];
        $this->assertSame("d3", $document["name"]);
        $this->assertSame(OtherTable::TABLE_NAME, $document["_foreignTable"]);
        $this->assertSame("documentList2", $document["_foreignName"]);
        $this->assertSame($id, $document["_foreignId"]);

    }

    public function testFromArrayConversion()
    {
        $array = '{"id":"d97a1150fa8-0f9a-ff7f-476b9e0aed97","documentList":[{"id":null,"name":"d1","_foreignTable":"Report","_foreignName":"documentList","_foreignId":"d97a1150fa8-0f9a-ff7f-476b9e0aed97"},{"id":null,"name":"d2","_foreignTable":"Report","_foreignName":"documentList","_foreignId":"d97a1150fa8-0f9a-ff7f-476b9e0aed97"}],"documentList2":[{"id":null,"name":"d3","_foreignTable":"Report","_foreignName":"documentList2","_foreignId":"d97a1150fa8-0f9a-ff7f-476b9e0aed97"}]}';

        $otherTable = new OtherTable();
        $otherTable->fromJSON($array);

        $documentList = $otherTable->getDocumentList();
        $this->assertCount(2, $documentList);

        foreach ($documentList as $index => $document) {
            $this->assertSame("d" . ($index + 1), $document->getName());
        }

        $documentList2 = $otherTable->getDocumentList2();
        $this->assertCount(1, $documentList2);
        $this->assertSame("d3", $documentList2[0]->getName());

    }

}