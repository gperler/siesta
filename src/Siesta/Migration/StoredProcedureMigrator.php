<?php
declare(strict_types=1);

namespace Siesta\Migration;

use Siesta\Database\StoredProcedureDefinition;
use Siesta\Database\StoredProcedureFactory;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class StoredProcedureMigrator
{

    /**
     * @var StoredProcedureFactory
     */
    private StoredProcedureFactory $factory;


    /**
     * @var StoredProcedureDefinition[]
     */
    private array $activeStoredProcedureList;

    /**
     * @var StoredProcedureDefinition[]
     */
    private array $neededProcedureList;

    /**
     * @var string[]
     */
    private array $statementList;


    /**
     * @var DataModel
     */
    private DataModel $dataModel;

    /**
     * @var Entity
     */
    private Entity $entity;

    /**
     * StoredProcedureMigrator constructor.
     * @param StoredProcedureFactory $factory
     * @param array $activeStoredProcedureList
     * @param StoredProcedureDefinition $sequencerStoredProcedure
     */
    public function __construct(StoredProcedureFactory $factory, array $activeStoredProcedureList, StoredProcedureDefinition $sequencerStoredProcedure)
    {
        $this->factory = $factory;
        $this->activeStoredProcedureList = $activeStoredProcedureList;
        $this->neededProcedureList = [$sequencerStoredProcedure];
        $this->statementList = [];

    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     */
    public function createProcedureStatementList(DataModel $dataModel, Entity $entity): void
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;

        $this->addSimpleStoredProcedureStatementList();
        $this->addCustomStoredProcedureStatementList();
        $this->addCollectionStoredProcedureList();
        $this->addCollectionManyStoredProcedureList();
        $this->addDynamicCollectionStoreProcedureList();
    }

    /**
     *
     */
    protected function addSimpleStoredProcedureStatementList(): void
    {
        $selectDefinition = $this->factory->createSelectByPKStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($selectDefinition);

        $insertDefinition = $this->factory->createInsertStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($insertDefinition);

        $updateDefinition = $this->factory->createUpdateStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($updateDefinition);

        $deleteDefinition = $this->factory->createDeleteByPKStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($deleteDefinition);

        if ($this->entity->getIsReplication()) {
            $copyToReplication = $this->factory->createCopyToReplicationTableStoredProcedure($this->dataModel, $this->entity);
            $this->addNeededProcedure($copyToReplication);
        }
    }

    /**
     *
     */
    protected function addCustomStoredProcedureStatementList(): void
    {
        foreach ($this->entity->getStoredProcedureList() as $storedProcedure) {
            $procedureDefinition = $this->factory->createCustomStoredProcedure($this->dataModel, $this->entity, $storedProcedure);
            $this->addNeededProcedure($procedureDefinition);
        }
    }

    /**
     *
     */
    protected function addCollectionStoredProcedureList(): void
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            if (!$reference->doesCollectionRefersTo()) {
                continue;
            }
            $procedureDefinition = $this->factory->createSelectByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addNeededProcedure($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addNeededProcedure($procedureDefinition);
        }
    }

    /**
     *
     */
    protected function addCollectionManyStoredProcedureList(): void
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $procedureDefinition = $this->factory->createSelectByCollectionManyStoredProcedure($this->dataModel, $this->entity, $collectionMany);
            $this->addNeededProcedure($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteByCollectionManyStoredProcedure($this->dataModel, $this->entity, $collectionMany);
            $this->addNeededProcedure($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteCollectionManyAssignmentStoredProcedure($this->dataModel, $this->entity, $collectionMany);
            $this->addNeededProcedure($procedureDefinition);
        }
    }

    /**
     * @return void
     */
    protected function addDynamicCollectionStoreProcedureList(): void
    {
        if (!$this->entity->getIsDynamicCollectionTarget()) {
            return;
        }
        $procedureDefinition = $this->factory->createDeleteByDynamicCollectionProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($procedureDefinition);

        $procedureDefinition = $this->factory->createSelectByDynamicCollectionProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($procedureDefinition);
    }

    /**
     * @param StoredProcedureDefinition $definition
     */
    protected function addNeededProcedure(StoredProcedureDefinition $definition): void
    {
        $this->neededProcedureList[] = $definition;
    }


    /**
     * @return array
     */
    public function getStoredProcedureMigrationList(): array
    {
        $this->statementList = [];
        $processedProcedureList = [];

        foreach ($this->neededProcedureList as $neededStoreProcedure) {
            $activeStoreProcedure = $this->getActiveStoredProcedureByName($neededStoreProcedure->getProcedureName());

            // does not exist > create
            if ($activeStoreProcedure === null) {
                $this->addStatement($neededStoreProcedure->getCreateProcedureStatement());
                $processedProcedureList[] = $neededStoreProcedure->getProcedureName();
                continue;
            }

            // does exist but differs > drop and create
            if (!$this->areStoredProceduresIdentical($activeStoreProcedure, $neededStoreProcedure)) {
                $this->addStatement($neededStoreProcedure->getDropProcedureStatement());
                $this->addStatement($neededStoreProcedure->getCreateProcedureStatement());
            }

            $processedProcedureList[] = $neededStoreProcedure->getProcedureName();

        }

        foreach ($this->activeStoredProcedureList as $activeStoredProcedure) {
            if (!in_array($activeStoredProcedure->getProcedureName(), $processedProcedureList)) {
                $this->addStatement($activeStoredProcedure->getDropProcedureStatement());
            }
        }
        return $this->statementList;
    }

    /**
     * @param StoredProcedureDefinition $active
     * @param StoredProcedureDefinition $needed
     * @return bool
     */
    private function areStoredProceduresIdentical(StoredProcedureDefinition $active, StoredProcedureDefinition $needed): bool
    {
        return $active->getCreateProcedureStatement() === $needed->getCreateProcedureStatement();
    }


    /**
     * @param string|null $statement
     */
    private function addStatement(string $statement = null): void
    {
        if ($statement === null) {
            return;
        }
        $this->statementList[] = $statement;
    }


    /**
     * @param string $procedureName
     * @return StoredProcedureDefinition|null
     */
    private function getActiveStoredProcedureByName(string $procedureName): ?StoredProcedureDefinition
    {
        foreach ($this->activeStoredProcedureList as $activeStoredProcedure) {
            if ($activeStoredProcedure->getProcedureName() === $procedureName) {
                return $activeStoredProcedure;
            }
        }
        return null;
    }


}