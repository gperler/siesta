<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

class MySQLCopyToReplicationStoredProcedure extends MySQLStoredProcedureBase
{

    const DELETE_FROM_MEMORY_TABLE = "DELETE FROM %s;";

    const INSERT_INTO_MEMORY_TABLE = "INSERT INTO %s SELECT * FROM %s;";

    /**
     * MySQLDeleteStoredProcedure constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        parent::__construct($dataModel, $entity);
        $this->buildElements();
    }

    /**
     *
     */
    protected function buildElements(): void
    {

        $this->modifies = true;

        $this->name = StoredProcedureNaming::getCopyToMemoryTable($this->entity);

        $this->determineTableNames();

        $this->signature = "()";

        $this->buildStatement();
    }

    /**
     * @return string|null
     */
    public function getCreateProcedureStatement(): ?string
    {
        if (!$this->isReplication) {
            return null;
        }

        return parent::getCreateProcedureStatement();
    }

    /**
     *
     */
    protected function buildStatement(): void
    {
        $this->statement = sprintf(self::DELETE_FROM_MEMORY_TABLE, $this->replicationTableName);
        $this->statement .= sprintf(self::INSERT_INTO_MEMORY_TABLE, $this->replicationTableName, $this->tableName);
    }

}