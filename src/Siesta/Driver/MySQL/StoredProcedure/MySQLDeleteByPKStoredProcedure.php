<?php
declare(strict_types=1);
namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\DelimitAttributeList;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MySQLDeleteByPKStoredProcedure extends MySQLStoredProcedureBase
{
    const DELIMIT_DELETE = "UPDATE %s SET %s = NOW() WHERE %s AND %s IS NULL;";

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

        $this->name = StoredProcedureNaming::getDeleteByPrimaryKeyName($this->entity);

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return null|string
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
     *
     */
    protected function buildStatement(): void
    {
        $this->statement = $this->buildDeleteSQL($this->tableName);

        if ($this->entity->getIsDelimit()) {
            $this->statement .= $this->buildDelimitDeleteSQL();
        }

        if ($this->isReplication) {
            $this->statement .= $this->buildDeleteSQL($this->replicationTableName);
        }

    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function buildDeleteSQL(string $tableName): string
    {

        $whereList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $whereList[] = $this->buildWherePart($attribute);
        }
        $where = $this->buildWhereAndSnippet($whereList);

        return sprintf(self::DELETE_WHERE, $tableName, $where);

    }

    /**
     * @return string
     */
    protected function buildDelimitDeleteSQL(): string
    {
        $whereList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $whereList[] = $this->buildWherePart($attribute);
        }
        $where = $this->buildWhereAndSnippet($whereList);

        $validUntilColumn = $this->quote(DelimitAttributeList::COLUMN_VALID_UNTIL);

        return sprintf(self::DELIMIT_DELETE, $this->delimitTable, $validUntilColumn, $where, $validUntilColumn);

    }

}