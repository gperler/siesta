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
class MySQLSelectByDynamicCollectionProcedure extends MySQLStoredProcedureBase
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
    protected function buildElements(): void
    {
        $this->modifies = false;

        $this->name = StoredProcedureNaming::getSelectByDynamicCollectionName($this->entity);

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
        foreach (DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this->entity) as $attribute) {
            $signatureList[] = $this->buildSignatureParameter($attribute->getStoredProcedureParameterName(), $attribute->getDbType());
        }
        $this->signature = $this->buildSignatureFromList($signatureList);
    }

    /**
     * @return void
     */
    protected function buildStatement(): void
    {
        $whereList = [];
        foreach (DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this->entity) as $attribute) {
            $whereList[] = $this->buildWherePart($attribute);
        }

        $where = $this->buildWhereAndSnippet($whereList);
        if ($this->isReplication) {
            $this->statement = sprintf(self::SELECT_WHERE, $this->delimitTable, $where);
        } else {
            $this->statement = sprintf(self::SELECT_WHERE, $this->tableName, $where);
        }
    }

}