<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\StoredProcedureDefinition;
use Siesta\Database\StoredProcedureFactory;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;
use Siesta\Model\StoredProcedure;

class TestStoredProcedureFactory implements StoredProcedureFactory
{

    /**
     * @param Entity $entity
     * @param $spName
     * @return TestStoredProcedureDefinition
     */
    private function createStoredProcedureDefinition(Entity $entity, $spName)
    {
        $tableName = $entity->getTableName();
        $name = $spName . '_' . $tableName;
        $create = 'create ' . $name;
        $drop = 'drop ' . $name;
        return new TestStoredProcedureDefinition($name, $drop, $create);
    }

    public function createDropStatementForProcedureName(string $procedureName): string
    {
        return "DROP $procedureName";
    }

    public function createCustomStoredProcedure(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "custom_" . $storedProcedure->getName());
    }

    public function createInsertStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "insert");
    }

    public function createUpdateStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "update");
    }

    public function createDeleteByPKStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "delete");
    }

    public function createSelectByPKStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "select");
    }

    public function createCopyToReplicationTableStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "create_copy_to_replication");
    }

    public function createSelectByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "select_by_reference");
    }

    public function createDeleteByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "delete_by_reference");
    }

    public function createSelectByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "select_by_collection_many");
    }

    public function createDeleteByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "delete_by_collection_many");
    }

    public function createDeleteCollectionManyAssignmentStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "delete_by_collection_many");
    }

    public function createSelectByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "select_by_dynamic_collection");
    }

    public function createDeleteByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return $this->createStoredProcedureDefinition($entity, "delete_by_dynamic_collection");
    }

}