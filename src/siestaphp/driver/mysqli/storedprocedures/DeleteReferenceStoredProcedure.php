<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class DeleteReferenceStoredProcedure extends MySQLStoredProcedureBase
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

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = true;

        $this->name = $this->referenceSource->getStoredProcedureDeleterName();

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature()
    {
        $sqlList = array();
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $sqlList[] = $this->buildSignatureParameterPart($column);
        }
        $this->signature = $this->buildSignatureSnippet($sqlList);
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $whereList = array();
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $whereList[] = $this->buildWherePart($column);
        }

        $where = $this->buildWhereSnippet($whereList);

        $this->statement = sprintf(self::DELETE_WHERE, $this->tableName, $where);
    }

}