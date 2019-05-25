<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\CreateStatementFactory;
use Siesta\Database\StoredProcedureDefinition;
use Siesta\Driver\MySQL\MetaData\MySQLStoredProcedure;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class TestCreateStatementFactory implements CreateStatementFactory
{

    const SEQUENCER_TABLE_CREATE = 'SEQUENCER_TABLE_CREATE';

    public function buildSequencer(): array
    {
        return ["Build Sequencer"];
    }

    public function buildSequencerStoredProcedure(): StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition("sequencer", "drop sequencer", "create sequencer");
    }

    public function buildSequencerTable(): string
    {
        return self::SEQUENCER_TABLE_CREATE;
    }


    public function buildCreateTable(Entity $entity): array
    {
        return ["create table '" . $entity->getTableName() . "'"];
    }

    public function buildCreateDelimitTable(Entity $entity): array
    {
        return ["create delimit table '" . $entity->getDelimitTableName() . "'"];
    }

}