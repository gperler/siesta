<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;

/**
 * Class CustomStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class CustomStoredProcedure extends MySQLStoredProcedureBase
{

    const TABLE_PLACE_HOLDER = "!TABLE!";

    /**
     * @var StoredProcedureSource
     */
    protected $storedProcedureSource;

    /**
     * @param StoredProcedureSource $source
     * @param EntityGeneratorSource $eds
     * @param $replication
     */
    public function __construct(StoredProcedureSource $source, EntityGeneratorSource $eds, $replication)
    {
        parent::__construct($eds, $replication);

        $this->storedProcedureSource = $source;

        $this->modifies = $this->storedProcedureSource->modifies();
    }

    /**
     * @return string[]
     */
    public function buildCreateProcedureStatement()
    {

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

    protected function buildName()
    {
        $this->name = $this->storedProcedureSource->getDatabaseName();
    }

    protected function buildSignature()
    {
        $this->signature = "(";
        foreach ($this->storedProcedureSource->getParameterList() as $parameter) {
            $this->signature .= "IN " . $parameter->getStoredProcedureName() . " " . $parameter->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",") . ")";
    }

    protected function buildStatement()
    {
        $sql = $this->storedProcedureSource->getSql("mysql");

        $this->statement = $this->replaceTable($sql, $this->tableName);

    }

    /**
     * @param $sql
     * @param $tableName
     *
     * @return string
     */
    protected function replaceTable($sql, $tableName)
    {
        return str_replace(self::TABLE_PLACE_HOLDER, $tableName, $sql);
    }

}