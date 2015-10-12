<?php

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\entity\EntityTransformerSource;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\mysqli\storedprocedures\CustomStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\DeleteReferenceStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\DeleteStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\InsertStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectReferenceStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\SelectStoredProcedure;
use siestaphp\driver\mysqli\storedprocedures\UpdateStoredProcedure;
use siestaphp\driver\TableBuilder;
use siestaphp\runtime\ServiceLocator;

/**
 * Class MySQLTableBuilder
 * @package siestaphp\driver\mysqli
 */
class MySQLTableBuilder implements TableBuilder
{

    /**
     * @param EntityTransformerSource $ets
     */
    public function setupTables(EntityTransformerSource $ets)
    {
        $tableBuilder = new TableCreator();
        $tableBuilder->setupTable($ets);
    }

    /**
     * @param EntityTransformerSource $ets
     */
    public function setupStoredProcedures(EntityTransformerSource $ets)
    {
        $connection = ConnectionFactory::getConnection();

        $insert = new InsertStoredProcedure($ets, false);
        $insert->createProcedure($connection);

        $select = new SelectStoredProcedure($ets, false);
        $select->createProcedure($connection);

        $update = new UpdateStoredProcedure($ets, false);
        $update->createProcedure($connection);

        $delete = new DeleteStoredProcedure($ets, false);
        $delete->createProcedure($connection);

        foreach ($ets->getReferenceSourceList() as $reference) {
            $referenceBuilder = new SelectReferenceStoredProcedure($ets, $reference, false);
            $referenceBuilder->createProcedure($connection);

            $referenceDeleter = new DeleteReferenceStoredProcedure($ets, $reference, false);
            $referenceDeleter->createProcedure($connection);

        }

        foreach ($ets->getStoredProcedureSourceList() as $sp) {
            $spBuilder = new CustomStoredProcedure($sp, $ets, false);
            $spBuilder->createProcedure($connection);
        }

    }
}