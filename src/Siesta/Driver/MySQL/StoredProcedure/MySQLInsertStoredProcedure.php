<?php
declare(strict_types=1);
namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MySQLInsertStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @var InsertStatement
     */
    protected InsertStatement $insertStatement;

    /**
     * InsertStoredProcedure constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        parent::__construct($dataModel, $entity);

        $this->insertStatement = new InsertStatement($entity);

        $this->determineTableNames();

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements(): void
    {

        $this->modifies = true;

        $this->name = StoredProcedureNaming::getSPInsertName($this->entity);

        $this->signature = $this->insertStatement->buildSignature();

        $this->statement = $this->insertStatement->buildInsert($this->tableName);

        if ($this->entity->getIsDelimit()) {
            $this->statement .= $this->insertStatement->buildDelimitInsert();
        }

        if ($this->isReplication) {
            $this->statement .= $this->insertStatement->buildInsert($this->replicationTableName);
        }

    }

}