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

/**
 * Class MySQLCreateStatementFactory
 * @package siestaphp\driver\mysqli
 */
class MySQLCreateStatementFactory implements CreateStatementFactory
{

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