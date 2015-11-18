<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLDriver;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectDelimitStoredProcedure extends MySQLStoredProcedureBase
{

    const TIME_PARAMETER = "IN P_TIME DATETIME";
    const SELECT_AT_TIME = "SELECT * FROM %s WHERE %s AND (_validFrom <= P_TIME) AND (_validUntil IS NULL OR _validUntil > P_TIME);";

    /**
     * @param EntityGeneratorSource $source
     */
    public function __construct(EntityGeneratorSource $source)
    {
        parent::__construct($source, false);

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = false;

        $this->name = StoredProcedureNaming::getSPFindByPrimaryKeyDelimitName($this->entitySource->getTable());

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
        $signaturePartList = [self::TIME_PARAMETER];
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $signaturePartList[] = $this->buildSignatureParameterPart($column);
        }

        $this->signature = $this->buildSignatureSnippet($signaturePartList);
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $wherePartList = [];
        foreach ($this->entitySource->getPrimaryKeyColumns() as $column) {
            $wherePartList[] = $this->buildWherePart($column);
        }
        $where = $this->buildWhereSnippet($wherePartList);

        $this->statement = sprintf(self::SELECT_AT_TIME, $this->delimitTable, $where);

    }

}