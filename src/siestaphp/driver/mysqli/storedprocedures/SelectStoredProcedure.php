<?php


namespace siestaphp\driver\mysqli\storedprocedures;


use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\driver\Driver;

use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectStoredProcedure extends StoredProcedureBase
{


    /**
     * @param EntityDatabaseSource $eds
     * @param $replication
     */
    public function __construct(EntityDatabaseSource $eds, $replication)
    {
        parent::__construct($eds, $replication);
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
        $this->name = StoredProcedureNaming::getSPFindByPrimaryKeyName($this->entityDatabaseSource->getTable());
    }


    /**
     *
     */
    protected function buildSignature()
    {
        $this->signature = "(";
        foreach ($this->entityDatabaseSource->getAttributeDatabaseSourceList() as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $this->signature .= "IN " . $attribute->getSQLParameterName() . " " . $attribute->getDatabaseType() . ",";
            }
        }
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }


    protected function buildStatement()
    {
        $where = "";
        foreach ($this->entityDatabaseSource->getAttributeDatabaseSourceList() as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $where .= $attribute->getDatabaseName() . " = " . $attribute->getSQLParameterName() . " AND ";
            }
        }

        $where = substr($where, 0, -5);
        $tableName = $this->quote($this->tableName);

        $this->statement = "SELECT * FROM $tableName WHERE $where;";

    }


}