<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\Attribute;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;

class MySQLDeleteByCollectionManyStoredProcedure extends MySQLStoredProcedureBase
{

    const JOIN = "DELETE %s FROM %s LEFT JOIN %s ON %s WHERE %s;";

    /**
     * @var CollectionMany
     */
    protected CollectionMany $collectionMany;

    /**
     * @var Entity|null
     */
    protected ?Entity $foreignEntity;

    /**
     * @var Reference|null
     */
    protected ?Reference $foreignReference;

    /**
     * @var Entity|null
     */
    protected ?Entity $mappingEntity;

    /**
     * @var Reference
     */
    protected Reference $mappingReference;

    public function __construct(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany)
    {
        parent::__construct($dataModel, $entity);
        $this->collectionMany = $collectionMany;
        $this->foreignEntity = $collectionMany->getForeignEntity();
        $this->foreignReference = $collectionMany->getForeignReference();
        $this->mappingEntity = $collectionMany->getMappingEntity();
        $this->mappingReference = $collectionMany->getMappingReference();
        $this->buildElements();
    }

    /**
     *
     */
    protected function buildElements(): void
    {

        $this->modifies = false;

        $this->name = StoredProcedureNaming::getDeleteByCollectionManyName($this->collectionMany);

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();
    }

    /**
     * @return void
     */
    protected function buildSignature(): void
    {
        $signaturePart = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $parameterName = $this->buildParameterName("L", $this->entity, $pkAttribute);
            $signaturePart[] = $this->buildSignatureParameter($parameterName, $pkAttribute->getDbType());
        }

        foreach ($this->foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $parameterName = $this->buildParameterName("F", $this->foreignEntity, $pkAttribute);
            $signaturePart[] = $this->buildSignatureParameter($parameterName, $pkAttribute->getDbType());
        }

        $this->signature = $this->buildSignatureFromList($signaturePart);
    }

    /**
     *
     */
    protected function buildStatement(): void
    {
        $foreignTable = $this->quote($this->foreignEntity->getTableName());
        $mappingTable = $this->quote($this->mappingEntity->getTableName());
        $joinCondition = $this->getJoinCondition();
        $whereStatement = $this->getWhereCondition();

        $this->statement = sprintf(self::JOIN, $foreignTable, $foreignTable, $mappingTable, $joinCondition, $whereStatement);
    }

    /**
     *
     */
    protected function getJoinCondition(): string
    {
        $foreignTable = $this->foreignEntity->getTableName();
        $mappingTable = $this->mappingEntity->getTableName();

        $conditionList = [];
        foreach ($this->foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $mappingAttribute = $this->getCorrespondingColumnForPKAttribute($this->foreignReference, $pkAttribute);
            $foreignAttribute = $pkAttribute->getDBName();
            $conditionList[] = $this->buildComparision($foreignTable, $foreignAttribute, $mappingTable, $mappingAttribute);
        }
        return implode(" AND ", $conditionList);
    }

    /**
     * @return string
     */
    protected function getWhereCondition(): string
    {
        $mappingTable = $this->mappingEntity->getTableName();

        $whereList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $mappingAttribute = $this->getCorrespondingColumnForPKAttribute($this->mappingReference, $pkAttribute);
            $whereList[] = $this->buildTableColumn($mappingTable, $mappingAttribute) . ' = ' . $this->buildParameterName("L", $this->entity, $pkAttribute);
        }

        foreach ($this->foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $mappingAttribute = $this->getCorrespondingColumnForPKAttribute($this->foreignReference, $pkAttribute);
            $mappingAttribute = $this->quote($mappingAttribute);
            $spParam = $this->buildParameterName("F", $this->foreignEntity, $pkAttribute);
            $whereList[] = "($spParam IS NULL OR $mappingAttribute = $spParam)";
        }
        return implode(" AND ", $whereList);
    }

    /**
     * @param Reference $reference
     * @param Attribute $pkAttribute
     *
     * @return string
     */
    protected function getCorrespondingColumnForPKAttribute(Reference $reference, Attribute $pkAttribute): string
    {
        foreach ($reference->getReferenceMappingList() as $referenceMapping) {
            if ($referenceMapping->getForeignAttributeName() === $pkAttribute->getDBName()) {
                return $referenceMapping->getLocalColumnName();
            }
        }
        return '';
    }

    /**
     * @param string $tableName
     * @param string $columnName
     *
     * @return string
     */
    protected function buildTableColumn(string $tableName, string $columnName): string
    {
        return $this->quote($tableName) . '.' . $this->quote($columnName);
    }

    /**
     * @param string $table1
     * @param string $column1
     * @param string $table2
     * @param string $column2
     *
     * @return string
     */
    protected function buildComparision(string $table1, string $column1, string $table2, string $column2): string
    {
        return $this->buildTableColumn($table1, $column1) . ' = ' . $this->buildTableColumn($table2, $column2);
    }

    /**
     * @param string $prefix
     * @param Entity $entity
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function buildParameterName(string $prefix, Entity $entity, Attribute $attribute): string
    {
        return 'P_' . $prefix . '_' . $entity->getTableName() . '_' . $attribute->getDBName();
    }

}