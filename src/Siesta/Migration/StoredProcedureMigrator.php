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
    protected $factory;


    /**
     * @var StoredProcedureDefinition[]
     */
    private $activeStoredProcedureList;

    /**
     * @var StoredProcedureDefinition[]
     */
    protected $neededProcedureList;

    /**
     * @var string[]
     */
    protected $statementList;



    /**
     * @var DataModel
     */
    protected $dataModel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * StoredProcedureMigrator constructor.
     * @param StoredProcedureFactory $factory
     * @param StoredProcedureDefinition[] $activeStoredProcedureList
     */
    public function __construct(StoredProcedureFactory $factory, array $activeStoredProcedureList)
    {
        $this->factory = $factory;
        $this->activeStoredProcedureList = $activeStoredProcedureList;
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getMigrateProcedureStatementList(DataModel $dataModel, Entity $entity)
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->statementList = [];
        $this->neededProcedureList = [];

        $this->addSimpleStoredProcedureStatementList();
        $this->addCustomStoredProcedureStatementList();
        $this->addCollectionStoredProcedureList();
        $this->addCollectionManyStoredProcedureList();
        $this->addDynamicCollectionStoreProcedureList();

        return $this->statementList;
    }

    /**
     *
     */
    protected function addSimpleStoredProcedureStatementList()
    {
        $selectDefinition = $this->factory->createSelectByPKStoredProcedure($this->dataModel, $this->entity);
        $this->addStatement($selectDefinition);

        $insertDefinition = $this->factory->createInsertStoredProcedure($this->dataModel, $this->entity);
        $this->addStatement($insertDefinition);

        $updateDefinition = $this->factory->createUpdateStoredProcedure($this->dataModel, $this->entity);
        $this->addStatement($updateDefinition);

        $deleteDefinition = $this->factory->createDeleteByPKStoredProcedure($this->dataModel, $this->entity);
        $this->addStatement($deleteDefinition);

        $copyToReplication = $this->factory->createCopyToReplicationTableStoredProcedure($this->dataModel, $this->entity);
        $this->addStatement($copyToReplication);
    }

    /**
     *
     */
    protected function addCustomStoredProcedureStatementList()
    {
        foreach ($this->entity->getStoredProcedureList() as $storedProcedure) {
            $procedureDefinition = $this->factory->createCustomStoredProcedure($this->dataModel, $this->entity, $storedProcedure);
            $this->addStatement($procedureDefinition);
        }
    }

    /**
     *
     */
    protected function addCollectionStoredProcedureList()
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            if (!$reference->doesCollectionRefersTo()) {
                continue;
            }
            $procedureDefinition = $this->factory->createSelectByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addStatement($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addStatement($procedureDefinition);
        }
    }

    /**
     *
     */
    protected function addCollectionManyStoredProcedureList()
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $procedureDefinition = $this->factory->createSelectByCollectionManyStoredProcedure($this->dataModel, $this->entity, $collectionMany);
            $this->addStatement($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteByCollectionManyStoredProcedure($this->dataModel, $this->entity, $collectionMany);
            $this->addStatement($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteCollectionManyAssignmentStoredProcedure($this->dataModel, $this->entity, $collectionMany);
            $this->addStatement($procedureDefinition);
        }
    }

    protected function addDynamicCollectionStoreProcedureList()
    {
        if (!$this->entity->getIsDynamicCollectionTarget()) {
            return;
        }
        $procedureDefinition = $this->factory->createDeleteByDynamicCollectionProcedure($this->dataModel, $this->entity);
        $this->addStatement($procedureDefinition);

        $procedureDefinition = $this->factory->createSelectByDynamicCollectionProcedure($this->dataModel, $this->entity);
        $this->addStatement($procedureDefinition);
    }

    /**
     * @param StoredProcedureDefinition $definition
     */
    protected function addStatement(StoredProcedureDefinition $definition)
    {
        $this->neededProcedureList[] = $definition;

        $dropDefinition = $definition->getDropProcedureStatement();
        if ($dropDefinition !== null) {
            $this->statementList[] = $dropDefinition;
        }
        $createDefinition = $definition->getCreateProcedureStatement();
        if ($createDefinition !== null) {
            $this->statementList[] = $createDefinition;
        }
    }


    public function getStoredProcedureMigrationList(): array
    {
        $statementList = [];
        $processedProcedureList = [];

        foreach ($this->neededProcedureList as $neededStoreProcedure) {
            $activeStoreProcedure = $this->getActiveStoredProcedureByName($neededStoreProcedure->getProcedureName());

            // does not exist > create
            if ($activeStoreProcedure === null) {
                $statementList[] = $neededStoreProcedure->getCreateProcedureStatement();
                $processedProcedureList[] = $neededStoreProcedure->getProcedureName();
                continue;
            }

            // does exist but differs > drop and create
            if ($activeStoreProcedure->getCreateProcedureStatement() !== $neededStoreProcedure->getCreateProcedureStatement()) {
                $statementList[] = $activeStoreProcedure->getDropProcedureStatement();
                $statementList[] = $neededStoreProcedure->getCreateProcedureStatement();
                $processedProcedureList[] = $neededStoreProcedure->getProcedureName();
            }
        }

        foreach($this->activeStoredProcedureList as $activeStoredProcedure) {
            if (!in_array($activeStoreProcedure->getProcedureName(), $processedProcedureList)) {
                $statementList[] = $activeStoreProcedure->getDropProcedureStatement();
            }
        }
        return $statementList;
    }


    private function getActiveStoredProcedureByName(string $procedureName): StoredProcedureDefinition
    {
        foreach ($this->activeStoredProcedureList as $activeStoredProcedure) {
            if ($activeStoredProcedure->getProcedureName() === $procedureName) {
                return $activeStoredProcedure;
            }
        }
        return null;
    }


}