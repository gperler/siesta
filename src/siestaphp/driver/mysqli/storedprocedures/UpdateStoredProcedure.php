<?php

namespace siestaphp\driver\mysqli\storedprocedures;


use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\driver\Driver;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class UpdateStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class UpdateStoredProcedure extends StoredProcedureBase
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
        foreach ($this->entityDatabaseSource->getReferenceDatabaseSourceList() as $reference) {
            foreach ($reference->getReferenceColumnList() as $column) {
                $parameterName = $column->getSQLParameterName();
                $this->signature .= "IN $parameterName " . $column->getDatabaseType() . ",";
            }
        }

        // iterate attributes
        foreach ($this->entityDatabaseSource->getAttributeDatabaseSourceList() as $attribute) {
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
     * @return string
     */
    protected function buildUpdateSQL($tableName)
    {

        // initialize values and where statements
        $values = "";
        $where = "";

        // iterate references first
        foreach ($this->entityDatabaseSource->getReferenceDatabaseSourceList() as $reference) {
            foreach ($reference->getReferenceColumnList() as $referencedColumn) {
                $values .= $this->quote($referencedColumn->getDatabaseName()) . " = " . $referencedColumn->getSQLParameterName() . ",";
            }
        }

        // iterate attributes next
        foreach ($this->entityDatabaseSource->getAttributeDatabaseSourceList() as $attribute) {
            if (!$attribute->isPrimaryKey()) {
                $values .= $this->quote($attribute->getDatabaseName()) . " = " . $attribute->getSQLParameterName() . ",";
            } else {
                $where .= $this->quote($attribute->getDatabaseName()) . " = " . $attribute->getSQLParameterName() . " AND ";
            }
        }

        // assemble parts
        $tableName = $this->quote($tableName);
        $where =  substr($where, 0, -5);;
        $values = rtrim($values, ",");

        // create update statement
        return "UPDATE $tableName SET $values WHERE $where;";
    }


}