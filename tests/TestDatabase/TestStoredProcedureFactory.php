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
    public function createDropStatementForProcedureName(string $procedureName) : string
    {
        return "DROP $procedureName";
    }

    public function createCustomStoredProcedure(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createInsertStoredProcedure(DataModel $dataModel, Entity $entity) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createUpdateStoredProcedure(DataModel $dataModel, Entity $entity) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createDeleteByPKStoredProcedure(DataModel $dataModel, Entity $entity) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createSelectByPKStoredProcedure(DataModel $dataModel, Entity $entity) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createCopyToReplicationTableStoredProcedure(DataModel $dataModel, Entity $entity) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createSelectByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createDeleteByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createSelectByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createDeleteByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createDeleteCollectionManyAssignmentStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany) : StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createSelectByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

    public function createDeleteByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new TestStoredProcedureDefinition();
    }

}