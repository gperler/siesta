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
        $this->buildElements();
    }

    protected function buildElements()
    {
        $this->modifies = true;

        $this->name = StoredProcedureNaming::getSPDeleteByPrimaryKeyName($this->entitySource->getTable());

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return null|string
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
        foreach ($this->entitySource->getPrimaryKeyColumns() as $pkColumn) {
            $parameterList[] = $this->buildSignatureParameterPart($pkColumn);
        }
        $this->signature = $this->buildSignatureSnippet($parameterList);
    }

    protected function buildStatement()
    {
        $this->statement = $this->buildDeleteSQL($this->tableName);
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function buildDeleteSQL($tableName)
    {

        $whereList = array();
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $whereList[] = $this->buildWherePart($column);
        }
        $where = $this->buildWhereSnippet($whereList);

        return sprintf(self::DELETE_WHERE, $tableName, $where);
    }

}