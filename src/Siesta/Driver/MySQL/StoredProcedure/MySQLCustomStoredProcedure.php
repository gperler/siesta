<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\StoredProcedure;

/**
 * @author Gregor Müller
 */
class MySQLCustomStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @var StoredProcedure
     */
    protected StoredProcedure $storedProcedure;

    /**
     * MySQLCustomStoredProcedure constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param StoredProcedure $storedProcedure
     */
    public function __construct(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure)
    {
        parent::__construct($dataModel, $entity);

        $this->storedProcedure = $storedProcedure;

        $this->buildElements();

    }

    /**
     * @return void
     */
    protected function buildElements(): void
    {

        $this->modifies = $this->storedProcedure->getModifies();

        $this->name = $this->storedProcedure->getDBName();

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature(): void
    {

        $parameterList = [];
        foreach ($this->storedProcedure->getParameterList() as $parameter) {
            $parameterList[] = $this->buildSignatureParameter($parameter->getSpName(), $parameter->getDbType());
        }
        $this->signature = $this->buildSignatureFromList($parameterList);
    }

    /**
     * @return void
     */
    protected function buildStatement(): void
    {
        $sql = $this->storedProcedure->getStatement();

        if ($this->entity->getIsReplication()) {
            $this->buildStatementForReplication($sql);
        } else {
            $this->statement = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->tableName, $sql);
        }
    }

    /**
     * @param string $sql
     *
     * @return void
     */
    protected function buildStatementForReplication(string $sql): void
    {
        // in case of modifying procedures execute on both tables
        if ($this->modifies) {
            $this->statement = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->tableName, $sql);
            $this->statement .= str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->replicationTableName, $sql);
            return;
        }
        // in case of non modifying read from memory table only
        $this->statement = str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $this->replicationTableName, $sql);
    }

}