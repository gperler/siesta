<?php

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\storedprocedures\CustomStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\DeleteReferenceStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\DeleteStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\InsertStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\MySQLStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectReferenceStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\UpdateStoredProcedure;
use siestaphp\driver\CreateStatementFactory;
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
    public function setupSequencer()
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
    public function setupTables(EntityGeneratorSource $ets)
    {
        $statementList = array();
        $tableBuilder = new MySQLTableCreator();
        $statementList[] = $tableBuilder->setupTable($ets);

        return $statementList;

    }

    /**
     * @param EntityGeneratorSource $ets
     *
     * @return string[]
     */
    public function setupStoredProcedures(EntityGeneratorSource $ets)
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
     * @param EntityGeneratorSource $ets
     *
     * @return MySQLStoredProcedure[]
     */
    private function createStoredProcedureList(EntityGeneratorSource $ets)
    {
        $spList = array();

        $spList[] = new InsertStoredProcedure($ets, false);

        $spList[] = new SelectStoredProcedure($ets, false);
        $spList[] = new UpdateStoredProcedure($ets, false);
        $spList[] = new DeleteStoredProcedure($ets, false);

        foreach ($ets->getReferenceGeneratorSourceList() as $reference) {
            $spList[] = new SelectReferenceStoredProcedure($ets, $reference, false);
            $spList[] = new DeleteReferenceStoredProcedure($ets, $reference, false);
        }

        foreach ($ets->getStoredProcedureSourceList() as $sp) {
            $spList[] = new CustomStoredProcedure($sp, $ets, false);
        }

        return $spList;
    }
}