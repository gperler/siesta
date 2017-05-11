<?php
declare(strict_types=1);

namespace Siesta\Database;

use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;
use Siesta\Model\StoredProcedure;

/**
 * @author Gregor Müller
 */
interface StoredProcedureFactory
{

    /**
     * @param string $procedureName
     *
     * @return string
     */
    public function createDropStatementForProcedureName(string $procedureName): string;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createInsertStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createUpdateStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByPKStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByPKStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteCollectionManyAssignmentStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createCopyToReplicationTableStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param StoredProcedure $storedProcedure
     *
     * @return StoredProcedureDefinition
     */
    public function createCustomStoredProcedure(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure): StoredProcedureDefinition;

}