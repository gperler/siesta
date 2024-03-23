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
class MySQLSelectByReferenceStoredProcedure extends MySQLStoredProcedureBase
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
        $this->modifies = false;

        $this->name = StoredProcedureNaming::getSelectByReferenceName($this->entity, $this->reference);

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
            $signatureList[] = $this->buildSignatureParameter($localAttribute->getStoredProcedureParameterName(), $localAttribute->getDbType());
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

        $where = $this->buildWhereAndSnippet($whereList);
        if ($this->isReplication) {
            $this->statement = sprintf(self::SELECT_WHERE, $this->delimitTable, $where);
        } else {
            $this->statement = sprintf(self::SELECT_WHERE, $this->tableName, $where);
        }
    }

}