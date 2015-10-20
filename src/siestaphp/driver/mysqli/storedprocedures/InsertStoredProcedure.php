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
        $this->name = StoredProcedureNaming::getSPInsertName($this->entityGeneratorSource->getTable());
    }

    protected function buildSignature()
    {
        $parameter = "IN %s %s";
        $signature = array();

        foreach ($this->entityGeneratorSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $signature[] = sprintf($parameter, $column->getSQLParameterName(), $column->getDatabaseType());
            }
        }

        foreach ($this->entityGeneratorSource->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            $signature[] = sprintf($parameter, $attribute->getSQLParameterName(), $attribute->getDatabaseType());
        }

        $this->signature = "(" . implode(",", $signature) . ")";

    }

    protected function buildStatement()
    {
        $this->statement = $this->buildInsertSQL($this->entityGeneratorSource->getTable());

        if ($this->replication) {
            $table = Replication::getReplicationTableName($this->entityGeneratorSource->getTable());
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
        $columnList = array();
        $valueList = array();

        // iterate referenced columns first
        foreach ($this->entityGeneratorSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $columnList[] = $this->quote($column->getDatabaseName());
                $valueList[] = $column->getSQLParameterName();
            }
        }

        // iterate attributes
        foreach ($this->entityGeneratorSource->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            $columnList[] = $this->quote($attribute->getDatabaseName());
            $valueList[] = $attribute->getSQLParameterName();
        }

        // finalize components
        $tableName = $this->quote($tableName);
        $columnSQL = implode(",", $columnList);
        $valueSQL = implode(",", $valueList);

        // done
        return sprintf(self::INSERT_STATEMENT, $tableName, $columnSQL, $valueSQL);
    }

}