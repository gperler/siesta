<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use RuntimeException;
use Siesta\Database\StoredProcedureNaming;
use Siesta\Model\Attribute;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;

class MySQLSelectByCollectionManyStoredProcedure extends MySQLStoredProcedureBase
{

    const JOIN = "SELECT %s.* FROM %s LEFT JOIN %s ON %s WHERE %s;";

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


    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     */
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

        $this->name = StoredProcedureNaming::getSelectByCollectionManyName($this->collectionMany);

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
            $whereList[] = $this->buildTableColumn($mappingTable, $mappingAttribute) . ' = ' . $pkAttribute->getStoredProcedureParameterName();
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
            if ($referenceMapping->getForeignColumnName() === $pkAttribute->getDBName()) {
                return $referenceMapping->getLocalColumnName();
            }
        }
        throw new RuntimeException();
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

}