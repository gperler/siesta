<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class UpdateStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class UpdateStoredProcedure extends MySQLStoredProcedureBase
{



    /**
     * @var UpdateStatement
     */
    protected $updateStatement;

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

        $this->determineTableNames();

        $this->updateStatement = new UpdateStatement($source);

        $this->insertStatement = new InsertStatement($source);

        $this->buildElements();
    }

    protected function buildElements()
    {

        $this->modifies = true;

        $this->name = StoredProcedureNaming::getSPUpdateName($this->entitySource->getTable());

        $this->signature = $this->updateStatement->buildSignature();

        $this->statement = $this->updateStatement->buildUpdate($this->tableName);

        if ($this->entitySource->isDelimit()) {
            $this->statement .= $this->updateStatement->buildDelimitUpdate();
            $this->statement .= $this->insertStatement->buildDelimitInsert();
        }

        if ($this->isReplication) {
            $this->statement .= $this->updateStatement->buildUpdate($this->replicationTableName);
        }


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

}