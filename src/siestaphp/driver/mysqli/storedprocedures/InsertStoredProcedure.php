<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class InsertStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class InsertStoredProcedure extends MySQLStoredProcedureBase
{

    const INSERT_STATEMENT = "INSERT INTO %s ( %s ) VALUES ( %s );";

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
        $this->name = StoredProcedureNaming::getSPInsertName($this->entityDatabaseSource->getTable());
    }

    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->entityDatabaseSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $parameterName = $column->getSQLParameterName();
                $this->signature .= "IN $parameterName " . $column->getDatabaseType() . ",";
            }

        }

        foreach ($this->entityDatabaseSource->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            $parameterName = $attribute->getSQLParameterName();
            $this->signature .= "IN $parameterName " . $attribute->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    protected function buildStatement()
    {
        $this->statement = $this->buildInsertSQL($this->entityDatabaseSource->getTable());

        if ($this->replication) {
            $table = Replication::getReplicationTableName($this->entityDatabaseSource->getTable());
            $this->statement .= $this->buildInsertSQL($table);
        }
    }

    /**
     * builds the insert statement
     *
     * @param string $tableName
     *
     * @return string
     */
    protected function buildInsertSQL($tableName)
    {
        // initialize column and value list
        $columnList = "";
        $valueList = "";

        // iterate referenced columns first
        foreach ($this->entityDatabaseSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $columnList .= $this->quote($column->getDatabaseName()) . ",";
                $valueList .= $column->getSQLParameterName() . ",";
            }
        }

        // iterate attributes
        foreach ($this->entityDatabaseSource->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            $columnList .= $this->quote($attribute->getDatabaseName()) . ",";
            $valueList .= $attribute->getSQLParameterName() . ",";
        }

        // finalize components
        $tableName = $this->quote($tableName);
        $columnList = rtrim($columnList, ",");
        $valueList = rtrim($valueList, ",");

        // done
        return sprintf(self::INSERT_STATEMENT, $tableName, $columnList, $valueList);
    }

}