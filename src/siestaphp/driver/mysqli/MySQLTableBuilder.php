<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 15:38
 */

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\entity\EntityTransformerSource;
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
        $driver = ServiceLocator::getDriver();

        $insert = new InsertStoredProcedure($ets, false);
        $insert->createProcedure($driver);

        $select = new SelectStoredProcedure($ets, false);
        $select->createProcedure($driver);

        $update = new UpdateStoredProcedure($ets, false);
        $update->createProcedure($driver);

        $delete = new DeleteStoredProcedure($ets, false);
        $delete->createProcedure($driver);

        foreach ($ets->getReferenceSourceList() as $reference) {
            $referenceBuilder = new SelectReferenceStoredProcedure($ets, $reference, false);
            $referenceBuilder->createProcedure($driver);

            $referenceDeleter = new DeleteReferenceStoredProcedure($ets, $reference, false);
            $referenceDeleter->createProcedure($driver);

        }

        foreach ($ets->getStoredProcedureSourceList() as $sp) {
            $spBuilder = new CustomStoredProcedure($sp, $ets, false);
            $spBuilder->createProcedure($driver);
        }

    }
}