<?php

namespace siestaphp\driver\mysqli;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\CreateStatementFactory;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\driver\mysqli\storedprocedures\CustomStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\DeleteReferenceStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\DeleteStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\InsertStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\MySQLStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectCollectorStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectDelimitStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectReferenceFilterStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectReferenceStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\UpdateStoredProcedure;
use siestaphp\util\File;

/**
 * Class MySQLCreateStatementFactory
 * @package siestaphp\driver\mysqli
 */
class MySQLCreateStatementFactory implements CreateStatementFactory
{

    const CREATE_SEQUENCE_TABLE = "CREATE TABLE IF NOT EXISTS `%s` (`TECHNICALNAME` VARCHAR(120) NOT NULL PRIMARY KEY, `SEQ` INT  ) ENGINE = InnoDB;";

    const DROP_SEQUENCER_SP = "DROP PROCEDURE IF EXISTS %s";

    /**
     * @return string[]
     */
    public function buildSequencer()
    {
        $sequencerFile = new File(__DIR__ . "/installer/Sequencer.sql");

        $statementList = array();
        $statementList[] = sprintf(self::CREATE_SEQUENCE_TABLE, CreateStatementFactory::SEQUENCER_TABLE_NAME);
        $statementList[] = sprintf(self::DROP_SEQUENCER_SP, CreateStatementFactory::SEQUENCER_SP_NAME);
        $statementList[] = preg_replace('/\s\s+/', ' ', $sequencerFile->getContents());
        return $statementList;
    }

    /**
     * @param EntityGeneratorSource $ets
     *
     * @return string[]
     */
    public function buildCreateTable(EntityGeneratorSource $ets)
    {
        $statementList = array();
        $tableBuilder = new MySQLTableCreator($ets);
        $statementList = array_merge($tableBuilder->buildCreateTable(), $statementList);
        return $statementList;
    }

    /**
     * @param EntityGeneratorSource $entity
     *
     * @return string[]
     */
    public function buildCreateDelimitTable(EntityGeneratorSource $entity)
    {
        $statementList = array();
        $tableBuilder = new MySQLTableCreator($entity);
        $statementList[] = $tableBuilder->buildCreateDelimitTable();

        return $statementList;
    }

    /**
     * @param EntityGeneratorSource $ets
     *
     * @return string[]
     */
    public function buildStoredProceduresStatements(EntityGeneratorSource $ets)
    {
        $statementList = array();
        foreach ($this->createStoredProcedureList($ets) as $sp) {

            $dropStatement = $sp->buildProcedureDropStatement();
            if ($dropStatement !== null) {
                $statementList[] = $dropStatement;
            }

            $createStatement = $sp->buildCreateProcedureStatement();
            if ($createStatement) {
                $statementList[] = $createStatement;
            }

        }
        return $statementList;
    }

    /**
     * @param EntityGeneratorSource $source
     *
     * @return MySQLStoredProcedure[]
     */
    private function createStoredProcedureList(EntityGeneratorSource $source)
    {
        $isReplication = Replication::isReplication($source);

        $spList = array();

        $spList[] = new InsertStoredProcedure($source, $isReplication);
        $spList[] = new SelectStoredProcedure($source, $isReplication);
        $spList[] = new UpdateStoredProcedure($source, $isReplication);
        $spList[] = new DeleteStoredProcedure($source, $isReplication);

        foreach ($source->getReferenceGeneratorSourceList() as $reference) {
            $spList[] = new SelectReferenceStoredProcedure($source, $reference, $isReplication);
            $spList[] = new DeleteReferenceStoredProcedure($source, $reference, $isReplication);

            foreach ($reference->getCollectorFilterSourceList() as $filter) {
                $spList[] = new SelectReferenceFilterStoredProcedure($source,$reference,$filter, $isReplication);
            }
        }

        foreach ($source->getStoredProcedureSourceList() as $sp) {
            $spList[] = new CustomStoredProcedure($sp, $source, $isReplication);
        }

        if ($source->isDelimit()) {
            $spList[] = new SelectDelimitStoredProcedure($source);
        }

        foreach($source->getNMMappingSourceList() as $nmMapping) {
            $spList[] = new SelectCollectorStoredProcedure($source, $nmMapping, $isReplication);
        }



        return $spList;
    }
}