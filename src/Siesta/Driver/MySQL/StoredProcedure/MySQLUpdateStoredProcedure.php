<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MySQLUpdateStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @var UpdateStatement
     */
    protected UpdateStatement $updateStatement;

    /**
     * @var InsertStatement
     */
    protected InsertStatement $insertStatement;

    /**
     * MySQLUpdateStoredProcedure constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        parent::__construct($dataModel, $entity);

        $this->determineTableNames();

        $this->updateStatement = new UpdateStatement($entity);

        $this->insertStatement = new InsertStatement($entity);

        $this->buildElements();
    }

    protected function buildElements(): void
    {

        $this->modifies = true;

        $this->name = StoredProcedureNaming::getUpdateName($this->entity);

        $this->signature = $this->updateStatement->buildSignature();

        $this->statement = $this->updateStatement->buildUpdate($this->tableName);

        if ($this->entity->getIsDelimit()) {
            $this->statement .= $this->updateStatement->buildDelimitUpdate();
            $this->statement .= $this->insertStatement->buildDelimitInsert();
        }

        if ($this->isReplication) {
            $this->statement .= $this->updateStatement->buildUpdate($this->replicationTableName);
        }

    }

    /**
     * @return string|null
     */
    public function getCreateProcedureStatement(): ?string
    {

        if (!$this->entity->hasPrimaryKey()) {
            return null;
        }

        return parent::getCreateProcedureStatement();
    }

}