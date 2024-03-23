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

class MySQLDeleteCollectionManyAssignmentStoredProcedure extends MySQLStoredProcedureBase
{

    const DELETE = "DELETE FROM %s WHERE %s;";

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

        $this->name = StoredProcedureNaming::getDeleteCollectionManyAssignmentName($this->collectionMany);

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
        $mappingTable = $this->quote($this->mappingEntity->getTableName());
        $whereStatement = $this->getWhereCondition();
        $this->statement = sprintf(self::DELETE, $mappingTable, $whereStatement);
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
            $parameterName = $this->buildParameterName("L", $this->entity, $pkAttribute);
            $whereList[] = $this->buildTableColumn($mappingTable, $mappingAttribute) . ' = ' . $parameterName;
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