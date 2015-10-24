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

    /**
     * @param EntityGeneratorSource $source
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $source, $replication)
    {
        parent::__construct($source, $replication);

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = false;

        $this->name = StoredProcedureNaming::getSPFindByPrimaryKeyName($this->entitySource->getTable());

        $this->determineTableNames();

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
    protected function buildSignature()
    {
        $parameterList = array();
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $parameterList[] = $this->buildSignatureParameterPart($column);
        }
        $this->signature = $this->buildSignatureSnippet($parameterList);
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $whereList = array();
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $whereList[] = $this->buildWherePart($column);
        }

        $tableName = $this->isReplication ? $this->replicationTableName : $this->tableName;
        $where = $this->buildWhereSnippet($whereList);

        $this->statement = sprintf(self::SELECT_WHERE, $tableName, $where);

    }

}