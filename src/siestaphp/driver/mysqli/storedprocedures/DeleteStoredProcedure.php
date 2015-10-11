<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\driver\Driver;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class DeleteStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class DeleteStoredProcedure extends StoredProcedureBase
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
        $this->modifies = true;

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();

        if (!$this->entityDatabaseSource->hasPrimaryKey()) {
            return;
        }
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

    protected function buildName()
    {
        $this->name = StoredProcedureNaming::getSPDeleteByPrimaryKeyName($this->entityDatabaseSource->getTable());
    }

    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->entityDatabaseSource->getPrimaryKeyColumns() as $pkColumn) {
            $parameterName = $pkColumn->getSQLParameterName();
            $this->signature .= "IN $parameterName " . $pkColumn->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    protected function buildStatement()
    {
        $this->statement = $this->buildDeleteSQL($this->entityDatabaseSource->getTable());

        if ($this->replication) {
            $table = Replication::getReplicationTableName($this->entityDatabaseSource->getTable());
            $this->statement .= $this->buildDeleteSQL($table);
        }
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function buildDeleteSQL($tableName)
    {
        $where = "";

        foreach ($this->entityDatabaseSource->getPrimaryKeyColumns() as $column) {
            $where .= $this->quote($column->getDatabaseName()) . " = " . $column->getSQLParameterName() . " and ";
        }
        $tableName = $this->quote($tableName);
        $where = substr($where, 0, -5);

        return "DELETE FROM $tableName WHERE $where ;";
    }

}