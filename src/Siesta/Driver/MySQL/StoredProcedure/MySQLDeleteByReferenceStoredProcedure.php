<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
class MySQLDeleteByReferenceStoredProcedure extends MySQLStoredProcedureBase
{

    /**
     * @var Reference
     */
    protected Reference $reference;

    /**
     * SelectReferenceStoredProcedure constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     */
    public function __construct(DataModel $dataModel, Entity $entity, Reference $reference)
    {
        parent::__construct($dataModel, $entity);

        $this->reference = $reference;

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements(): void
    {
        $this->modifies = true;

        $this->name = StoredProcedureNaming::getDeleteByReferenceName($this->entity, $this->reference);

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature(): void
    {
        $signatureList = [];
        foreach ($this->reference->getReferenceMappingList() as $referenceMapping) {
            $localAttribute = $referenceMapping->getLocalAttribute();
            $signatureList[] = $this->buildSignatureParameterForAttribute($localAttribute);
        }

        foreach($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $signatureList[] = $this->buildSignatureParameterForAttribute($pkAttribute);
        }

        $this->signature = $this->buildSignatureFromList($signatureList);
    }

    /**
     * @return void
     */
    protected function buildStatement(): void
    {
        $whereList = [];
        foreach ($this->reference->getReferenceMappingList() as $referenceMapping) {
            $localAttribute = $referenceMapping->getLocalAttribute();
            $whereList[] = $this->buildWherePart($localAttribute);
        }

        foreach($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
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