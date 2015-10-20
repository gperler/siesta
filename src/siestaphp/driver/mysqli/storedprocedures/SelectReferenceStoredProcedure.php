<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectReferenceStoredProcedure extends MySQLStoredProcedureBase
{

    protected $referenceSource;

    /**
     * @param EntityGeneratorSource $source
     * @param ReferenceGeneratorSource $referenceSource
     * @param bool $replication
     */
    public function __construct(EntityGeneratorSource $source, ReferenceGeneratorSource $referenceSource, $replication)
    {
        parent::__construct($source, $replication);
        $this->referenceSource = $referenceSource;
    }

    /**
     * @return string
     */
    public function buildCreateProcedureStatement()
    {

        $this->modifies = false;

        $this->determineTableNames();

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

        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $this->signature .= "IN " . $column->getSQLParameterName() . " " . $column->getDatabaseType() . ",";
        }

        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    protected function buildStatement()
    {
        $where = "";
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $where .= $column->getDatabaseName() . " = " . $column->getSQLParameterName() . " AND ";
        }

        $where = substr($where, 0, -5);
        $tableName = $this->quote($this->tableName);

        $this->statement = "SELECT * FROM $tableName WHERE $where;";

    }

}