<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectReferenceFilterStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectReferenceFilterStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @var ReferenceGeneratorSource
     */
    protected $referenceSource;

    /**
     * @var CollectorFilterSource
     */
    protected $filterSource;

    /**
     * @param EntityGeneratorSource $source
     * @param ReferenceGeneratorSource $referenceSource
     * @param CollectorFilterSource $filterSource
     * @param bool $replication
     */
    public function __construct(EntityGeneratorSource $source, ReferenceGeneratorSource $referenceSource, CollectorFilterSource $filterSource, $replication)
    {
        parent::__construct($source, $replication);

        $this->referenceSource = $referenceSource;

        $this->filterSource = $filterSource;

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = false;

        $this->name = $this->filterSource->getSPName();

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature()
    {
        $signatureList = [];
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $signatureList[] = $this->buildSignatureParameterPart($column);
        }

        foreach ($this->filterSource->getSPParameterList() as $parameter) {
            $signatureList[] = sprintf(parent::SP_PARAMETER, $parameter->getStoredProcedureName(), $parameter->getDatabaseType());
        }

        $this->signature = $this->buildSignatureSnippet($signatureList);
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $whereList = [];
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $whereList[] = $this->buildWherePart($column);
        }

        $where = $this->buildWhereSnippet($whereList) . " AND " . $this->filterSource->getFilter();
        if ($this->isReplication) {
            $this->statement = sprintf(self::SELECT_WHERE, $this->delimitTable, $where);
        } else {
            $this->statement = sprintf(self::SELECT_WHERE, $this->tableName, $where);
        }
    }

}