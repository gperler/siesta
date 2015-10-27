<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class InsertStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class InsertStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @var InsertStatement
     */
    protected $insertStatement;

    /**
     * @param EntityGeneratorSource $source
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $source, $replication)
    {
        parent::__construct($source, $replication);

        $this->insertStatement = new InsertStatement($this->entitySource);

        $this->determineTableNames();

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {

        $this->modifies = true;

        $this->name = StoredProcedureNaming::getSPInsertName($this->entitySource->getTable());

        $this->signature = $this->insertStatement->buildSignature();

        $this->statement = $this->insertStatement->buildInsert($this->tableName);

        if ($this->entitySource->isDelimit()) {
            $this->statement .= $this->insertStatement->buildDelimitInsert();
        }

        if ($this->isReplication) {
            $this->statement .= $this->insertStatement->buildInsert($this->memoryTableName);
        }


    }

}