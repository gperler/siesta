<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLDriver;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectStoredProcedure extends MySQLStoredProcedureBase
{

    const SP_PARAMETER = "IN %s %s";

    const SELECT = "SELECT * FROM %s WHERE %s;";

    const WHERE = "%s = %s";

    /**
     * @param EntityGeneratorSource $source
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $source, $replication)
    {
        parent::__construct($source, $replication);

        $this->modifies = false;

        $this->determineTableNames();

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return string
     */
    public function buildCreateProcedureStatement()
    {
        if (!$this->entitySource->hasPrimaryKey()) {
            return null;
        }

        return parent::buildCreateProcedureStatement();
    }

    /**
     * @return void
     */
    protected function buildName()
    {
        $this->name = StoredProcedureNaming::getSPFindByPrimaryKeyName($this->entitySource->getTable());
    }

    /**
     * @return void
     */
    protected function buildSignature()
    {
        $parameterList = array();
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $parameterList[] = sprintf(self::SP_PARAMETER, $column->getSQLParameterName(), $column->getDatabaseType());
        }
        $this->signature = "(" . implode(",", $parameterList) . ")";
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $whereList = array();
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $dbName = MySQLDriver::quote($column->getDatabaseName());
            $whereList[] = sprintf(self::WHERE, $dbName, $column->getSQLParameterName());
        }

        $tableName = $this->quote($this->tableName);
        $where = implode(" AND ", $whereList);

        $this->statement = sprintf(self::SELECT, $tableName, $where);

    }

}