<?php


namespace siestaphp\driver\mysqli\storedprocedures;



use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\driver\Driver;

use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectReferenceStoredProcedure extends StoredProcedureBase
{

    protected $referenceSource;

    /**
     * @param EntityDatabaseSource $eds
     * @param ReferenceDatabaseSource $referenceSource
     * @param bool $replication
     */
    public function __construct(EntityDatabaseSource $eds, ReferenceDatabaseSource $referenceSource, $replication)
    {
        parent::__construct($eds, $replication);
        $this->referenceSource = $referenceSource;
    }

    /**
     * @param Driver $driver
     */
    public function createProcedure(Driver $driver)
    {

        $this->modifies = false;

        $this->determineTableNames();

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();

        $this->executeProcedureDrop($driver);

        $this->executeProcedureBuild($driver);
    }


    /**
     * @param Driver $driver
     */
    public function dropProcedure(Driver $driver)
    {
        $this->buildName();
        $this->executeProcedureDrop($driver);
    }

    /**
     *
     */
    protected function buildName()
    {
        $this->name = $this->referenceSource->getStoredProcedureFinderName();

    }


    /**
     *
     */
    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->referenceSource->getReferenceColumnList() as $column) {
            $this->signature .= "IN " . $column->getSQLParameterName() . " " . $column->getDatabaseType() . ",";
        }

        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }


    protected function buildStatement()
    {
        $where = "";
        foreach ($this->referenceSource->getReferenceColumnList() as $column) {
            $where .= $column->getDatabaseName() . " = " . $column->getSQLParameterName() . " AND ";
        }

        $where = substr($where, 0, -5);
        $tableName = $this->quote($this->tableName);

        $this->statement = "SELECT * FROM $tableName WHERE $where;";

    }


}