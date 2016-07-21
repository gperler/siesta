<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\CreateStatementFactory;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class TestCreateStatementFactory implements CreateStatementFactory
{
    public function buildSequencer() : array
    {
        return ["Build Sequencer"];
    }

    public function buildCreateTable(Entity $entity) : array
    {
        return ["create table '" . $entity->getTableName() . "'"];
    }

    public function buildCreateDelimitTable(Entity $entity) : array
    {
        return ["create delimit table '" . $entity->getDelimitTableName() . "'"];
    }

    public function buildStoredProceduresStatements(Entity $ets) : array
    {
        return [];
    }

}