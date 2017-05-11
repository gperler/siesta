<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\DynamicCollectionAttributeList;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MySQLDeleteByDynamicCollectionStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * SelectReferenceStoredProcedure constructor.
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
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = true;

        $this->name = StoredProcedureNaming::getDeleteByDynamicCollectionName($this->entity);

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
        foreach (DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this->entity) as $attribute) {
            $signatureList[] = $this->buildSignatureParameterForAttribute($attribute);
        }

        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $signatureList[] = $this->buildSignatureParameterForAttribute($pkAttribute);
        }

        $this->signature = $this->buildSignatureFromList($signatureList);
    }

    /**
     * @return void
     */
    protected function buildStatement()
    {
        $whereList = [];
        foreach (DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this->entity) as $attribut) {
            $whereList[] = $this->buildWherePart($attribut);
        }

        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $parameterName = $pkAttribute->getStoredProcedureParameterName();
            $dbName = $pkAttribute->getDBName();
            $whereList[] = "($parameterName IS NULL OR ($parameterName = $dbName))";
        }

        $where = $this->buildWhereAndSnippet($whereList);

        $this->statement = sprintf(self::DELETE_WHERE, $this->tableName, $where);

        if ($this->isReplication) {
            $this->statement .= sprintf(self::DELETE_WHERE, $this->delimitTable, $where);
        }

    }

}