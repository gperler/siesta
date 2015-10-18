<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\Connection;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class UpdateStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class UpdateStoredProcedure extends MySQLStoredProcedureBase
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
     * @return string
     */
    public function buildCreateProcedureStatement()
    {
        $this->modifies = true;

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

    protected function buildName()
    {
        $this->name = StoredProcedureNaming::getSPUpdateName($this->entityDatabaseSource->getTable());
    }

    /**
     * builds the signature
     */
    protected function buildSignature()
    {
        // start signature
        $this->signature = "(";

        // iterate references and their columns
        foreach ($this->entityDatabaseSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $parameterName = $column->getSQLParameterName();
                $this->signature .= "IN $parameterName " . $column->getDatabaseType() . ",";
            }
        }

        // iterate attributes
        foreach ($this->entityDatabaseSource->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            $parameterName = $attribute->getSQLParameterName();
            $this->signature .= "IN $parameterName " . $attribute->getDatabaseType() . ",";
        }

        // remove trailing , and close signature
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    /**
     * build the statement, considering table replication
     */
    protected function buildStatement()
    {
        $this->statement = $this->buildUpdateSQL($this->entityDatabaseSource->getTable());

        if ($this->replication) {
            $table = Replication::getReplicationTableName($this->entityDatabaseSource->getTable());
            $this->statement .= $this->buildUpdateSQL($table);
        }
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function buildUpdateSQL($tableName)
    {

        // initialize values and where statements
        $values = "";

        // iterate references first
        foreach ($this->entityDatabaseSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $referencedColumn) {
                $values .= $this->quote($referencedColumn->getDatabaseName()) . " = " . $referencedColumn->getSQLParameterName() . ",";
            }
        }

        // iterate attributes next
        foreach ($this->entityDatabaseSource->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            if (!$attribute->isPrimaryKey()) {
                $values .= $this->quote($attribute->getDatabaseName()) . " = " . $attribute->getSQLParameterName() . ",";
            }
        }

        // build where statement
        $where = "";
        foreach ($this->entityDatabaseSource->getPrimaryKeyColumns() as $column) {
            $where .= $this->quote($column->getDatabaseName()) . " = " . $column->getSQLParameterName() . " AND ";
        }

        // assemble parts
        $tableName = $this->quote($tableName);
        $where = substr($where, 0, -5);;
        $values = rtrim($values, ",");

        // create update statement
        return "UPDATE $tableName SET $values WHERE $where;";
    }

}