<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL;

use Siesta\Database\StoredProcedureDefinition;
use Siesta\Database\StoredProcedureFactory;
use Siesta\Driver\MySQL\StoredProcedure\MySQLCopyToReplicationStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLCustomStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLDeleteByCollectionManyStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLDeleteByDynamicCollectionStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLDeleteByPKStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLDeleteByReferenceStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLDeleteCollectionManyAssignmentStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLInsertStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLSelectByCollectionManyStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLSelectByDynamicCollectionProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLSelectByPKStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLSelectByReferenceStoredProcedure;
use Siesta\Driver\MySQL\StoredProcedure\MySQLUpdateStoredProcedure;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;
use Siesta\Model\StoredProcedure;

class MySQLStoredProcedureFactory implements StoredProcedureFactory
{
    const DROP_PROCEDURE = "DROP PROCEDURE IF EXISTS %s;";

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createInsertStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLInsertStoredProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createUpdateStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLUpdateStoredProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByPKStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLSelectByPKStoredProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference): StoredProcedureDefinition
    {
        return new MySQLSelectByReferenceStoredProcedure($dataModel, $entity, $reference);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition
    {
        return new MySQLSelectByCollectionManyStoredProcedure($dataModel, $entity, $collectionMany);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByPKStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLDeleteByPKStoredProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByReferenceStoredProcedure(DataModel $dataModel, Entity $entity, Reference $reference): StoredProcedureDefinition
    {
        return new MySQLDeleteByReferenceStoredProcedure($dataModel, $entity, $reference);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByCollectionManyStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition
    {
        return new MySQLDeleteByCollectionManyStoredProcedure($dataModel, $entity, $collectionMany);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteCollectionManyAssignmentStoredProcedure(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany): StoredProcedureDefinition
    {
        return new MySQLDeleteCollectionManyAssignmentStoredProcedure($dataModel, $entity, $collectionMany);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createSelectByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLSelectByDynamicCollectionProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createDeleteByDynamicCollectionProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLDeleteByDynamicCollectionStoredProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return StoredProcedureDefinition
     */
    public function createCopyToReplicationTableStoredProcedure(DataModel $dataModel, Entity $entity): StoredProcedureDefinition
    {
        return new MySQLCopyToReplicationStoredProcedure($dataModel, $entity);
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param StoredProcedure $storedProcedure
     *
     * @return StoredProcedureDefinition
     */
    public function createCustomStoredProcedure(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure): StoredProcedureDefinition
    {
        return new MySQLCustomStoredProcedure($dataModel, $entity, $storedProcedure);
    }

    /**
     * @param string $procedureName
     *
     * @return string
     */
    public function createDropStatementForProcedureName(string $procedureName): string
    {
        $procedureName = MySQLDriver::quote($procedureName);
        return sprintf(self::DROP_PROCEDURE, $procedureName);
    }

}