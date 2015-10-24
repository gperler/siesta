<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
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

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = false;

        $this->name = $this->referenceSource->getStoredProcedureFinderName();

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature()
    {
        $signatureList = array();
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $signatureList[] = $this->buildSignatureParameterPart($column);
        }

        $this->signature = $this->buildSignatureSnippet($signatureList);
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
        if ($this->isReplication) {
            $this->statement = sprintf(self::SELECT_WHERE, $this->delimitTable, $where);
        } else {
            $this->statement = sprintf(self::SELECT_WHERE, $this->tableName, $where);
        }
    }

}