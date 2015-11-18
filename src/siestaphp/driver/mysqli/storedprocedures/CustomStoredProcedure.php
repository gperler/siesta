<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\driver\ColumnMigrator;
use siestaphp\driver\mysqli\MySQLDriver;

/**
 * Class CustomStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class CustomStoredProcedure extends MySQLStoredProcedureBase
{

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

        $this->buildElements();

    }

    /**
     * @return void
     */
    protected function buildElements()
    {

        $this->modifies = $this->storedProcedureSource->modifies();

        $this->name = $this->storedProcedureSource->getDatabaseName();

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature()
    {
        $parameterList = [];
        foreach ($this->storedProcedureSource->getParameterList() as $parameter) {
            $parameterList[] .= sprintf(parent::SP_PARAMETER, $parameter->getStoredProcedureName(), $parameter->getDatabaseType());
        }
        $this->signature = $this->buildSignatureSnippet($parameterList);
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $sql = $this->storedProcedureSource->getSql(MySQLDriver::MYSQL_DRIVER_NAME);

        if ($this->isReplication) {
            $this->buildStatementForReplication($sql);
            return;
        }

        $this->statement = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->tableName, $sql);

    }

    /**
     * @param string $sql
     *
     * @return void
     */
    protected function buildStatementForReplication($sql)
    {
        // in case of modifying procedures execute on both tables
        if ($this->modifies) {
            $this->statement = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->tableName, $sql);
            $this->statement .= str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->memoryTableName, $sql);
            return;
        }
        // in case of non modifying read from memory table only
        $this->statement = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->memoryTableName, $sql);
    }

}