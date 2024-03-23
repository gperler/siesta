<?php
declare(strict_types=1);
namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MySQLSelectByPKStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * SelectStoredProcedure constructor.
     *
     * @param DataModel $model
     * @param Entity $entity
     */
    public function __construct(DataModel $model, Entity $entity)
    {
        parent::__construct($model, $entity);

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements(): void
    {
        $this->modifies = false;

        $this->name = StoredProcedureNaming::getSelectByPrimaryKeyName($this->entity);

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
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

    /**
     * @return void
     */
    protected function buildSignature(): void
    {
        $parameterList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $parameterList[] = $this->buildSignatureParameter($attribute->getStoredProcedureParameterName(), $attribute->getDbType());
        }
        $this->signature = $this->buildSignatureFromList($parameterList);
    }

    /**
     * @return void
     */
    protected function buildStatement(): void
    {
        $whereList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $whereList[] = $this->buildWherePart($attribute);
        }
        $where = $this->buildWhereAndSnippet($whereList);

        $tableName = $this->isReplication ? $this->replicationTableName : $this->tableName;

        $this->statement = sprintf(self::SELECT_WHERE, $tableName, $where);

    }

}