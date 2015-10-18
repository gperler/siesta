<?php
namespace siestaphp\tests\functional;

/**
 * Class ReferenceTest
 */
class SequencerTest extends SiestaTester
{

    protected function setUp()
    {
        $this->connectAndInstall();

        $factory = $this->connection->getCreateStatementFactory();

        $statementList = $factory->setupSequencer();

        foreach ($statementList as $statement) {
            $this->connection->query($statement);
        }


    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testSequencer()
    {
        $this->startTimer();
        for ($i = 1; $i < 100; $i++) {
            $this->assertSame($i, $this->connection->getSequence("A"));
            $this->assertSame($i, $this->connection->getSequence("B"));
            $this->assertSame($i, $this->connection->getSequence("C"));
        }
        $this->stopTimer("Sequencer time %0.2fms", 300);
    }

    public function testUUIDGenerator()
    {
        $uuid = \siestaphp\runtime\ServiceLocator::getUUIDGenerator()->uuid();
        $this->assertNotNull($uuid, "UUID null");
        $this->assertEquals(strlen($uuid), 36);
    }

}