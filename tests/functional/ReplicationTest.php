<?php

namespace siestaphp\tests\functional;

use siestaphp\tests\functional\replication\gen\Book;

/**
 * Class PerformanceTest
 */
class ReplicationTest extends SiestaTester
{

    const ASSET_PATH = "/replication";

    const SRC_XML = "/Replication.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();
    }

    protected function tearDown()
    {
        //$this->dropDatabase();
    }

    public function testInsert()
    {
        $book1 = new Book();
        $book1->setPrice(19.80);
        $book1->setTitle("Zieh Dich aus du alte Hippe");
        $book1->save();


        $bookReloaded = Book::getEntityByPrimaryKey($book1->getId());
        $this->assertNotNull($bookReloaded, "make sure book can be reloaded from memory table");


    }

}