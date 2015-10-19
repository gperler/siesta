<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @param EntityGeneratorSource $eds
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $eds, $replication)
    {
        parent::__construct($eds, $replication);
    }

    /**
     * @return null|string
     */
    public function buildCreateProcedureStatement()
    {

        $this->modifies = false;

        $this->determineTableNames();

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();

        if (!$this->entityDatabaseSource->hasPrimaryKey()) {
            return null;
        }

        return parent::buildCreateProcedureStatement();
    }

    /**
     * @return string
     */
    public function buildProcedureDropStatement()
    {
        $this->buildName();
        return parent::buildProcedureDropStatement();
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
        foreach ($this->entityDatabaseSource->getPrimaryKeyColumns() as $column) {
            $this->signature .= "IN " . $column->getSQLParameterName() . " " . $column->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    protected function buildStatement()
    {
        $where = "";
        foreach ($this->entityDatabaseSource->getPrimaryKeyColumns() as $column) {
            $where .= $column->getDatabaseName() . " = " . $column->getSQLParameterName() . " AND ";
        }

        $where = substr($where, 0, -5);
        $tableName = $this->quote($this->tableName);

        $this->statement = "SELECT * FROM $tableName WHERE $where;";

    }

}