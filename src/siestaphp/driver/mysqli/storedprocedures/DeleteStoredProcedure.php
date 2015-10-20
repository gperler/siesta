<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class DeleteStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class DeleteStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @param EntityGeneratorSource $source
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $source, $replication)
    {
        parent::__construct($source, $replication);
    }

    /**
     * @return null|string
     */
    public function buildCreateProcedureStatement()
    {
        $this->modifies = true;

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();

        if (!$this->entitySource->hasPrimaryKey()) {
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

    protected function buildName()
    {
        $this->name = StoredProcedureNaming::getSPDeleteByPrimaryKeyName($this->entitySource->getTable());
    }

    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->entitySource->getPrimaryKeyColumns() as $pkColumn) {
            $parameterName = $pkColumn->getSQLParameterName();
            $this->signature .= "IN $parameterName " . $pkColumn->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    protected function buildStatement()
    {
        $this->statement = $this->buildDeleteSQL($this->entitySource->getTable());

        if ($this->replication) {
            $table = Replication::getReplicationTableName($this->entitySource->getTable());
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

        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $where .= $this->quote($column->getDatabaseName()) . " = " . $column->getSQLParameterName() . " and ";
        }
        $tableName = $this->quote($tableName);
        $where = substr($where, 0, -5);

        return "DELETE FROM $tableName WHERE $where ;";
    }

}