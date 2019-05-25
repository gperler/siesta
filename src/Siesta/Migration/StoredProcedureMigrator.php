<?php
declare(strict_types=1);

namespace Siesta\Migration;

use Codeception\Util\Debug;
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
    private $factory;


    /**
     * @var StoredProcedureDefinition[]
     */
    private $activeStoredProcedureList;

    /**
     * @var StoredProcedureDefinition[]
     */
    private $neededProcedureList;

    /**
     * @var string[]
     */
    private $statementList;


    /**
     * @var DataModel
     */
    private $dataModel;

    /**
     * @var Entity
     */
    private $entity;

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
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     *
     */
    public function createProcedureStatementList(DataModel $dataModel, Entity $entity)
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
    protected function addSimpleStoredProcedureStatementList()
    {
        $selectDefinition = $this->factory->createSelectByPKStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($selectDefinition);

        $insertDefinition = $this->factory->createInsertStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($insertDefinition);

        $updateDefinition = $this->factory->createUpdateStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($updateDefinition);

        $deleteDefinition = $this->factory->createDeleteByPKStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($deleteDefinition);

        $copyToReplication = $this->factory->createCopyToReplicationTableStoredProcedure($this->dataModel, $this->entity);
        $this->addNeededProcedure($copyToReplication);
    }

    /**
     *
     */
    protected function addCustomStoredProcedureStatementList()
    {
        foreach ($this->entity->getStoredProcedureList() as $storedProcedure) {
            $procedureDefinition = $this->factory->createCustomStoredProcedure($this->dataModel, $this->entity, $storedProcedure);
            $this->addNeededProcedure($procedureDefinition);
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
            $this->addNeededProcedure($procedureDefinition);

            $procedureDefinition = $this->factory->createDeleteByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addNeededProcedure($procedureDefinition);
        }
    }

    /**
     *
     */
    protected function addCollectionManyStoredProcedureList()
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

    protected function addDynamicCollectionStoreProcedureList()
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
    protected function addNeededProcedure(StoredProcedureDefinition $definition)
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
                Debug::debug("Create");
                Debug::debug($neededStoreProcedure);
                $processedProcedureList[] = $neededStoreProcedure->getProcedureName();
                continue;
            }

            // does exist but differs > drop and create
            if (!$this->areStoredProceduresIdentical($activeStoreProcedure, $neededStoreProcedure)) {
                Debug::debug("change");
                Debug::debug($neededStoreProcedure);
                $this->addStatement($neededStoreProcedure->getDropProcedureStatement());
                $this->addStatement($neededStoreProcedure->getCreateProcedureStatement());
            }

            $processedProcedureList[] = $neededStoreProcedure->getProcedureName();

        }

        foreach ($this->activeStoredProcedureList as $activeStoredProcedure) {
            if (!in_array($activeStoredProcedure->getProcedureName(), $processedProcedureList)) {
                Debug::debug("drop");
                Debug::debug($activeStoredProcedure);
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
    private function areStoredProceduresIdentical(StoredProcedureDefinition $active, StoredProcedureDefinition $needed)
    {
        return $active->getCreateProcedureStatement() === $needed->getCreateProcedureStatement();
    }


    /**
     * @param string|null $statement
     */
    private function addStatement(string $statement = null)
    {
        if ($statement === null) {
            return;
        }
        $this->statementList[] = $statement;
    }


    /**
     * @param string $procedureName
     * @return StoredProcedureDefinition
     */
    private function getActiveStoredProcedureByName(string $procedureName)
    {
        foreach ($this->activeStoredProcedureList as $activeStoredProcedure) {
            if ($activeStoredProcedure->getProcedureName() === $procedureName) {
                return $activeStoredProcedure;
            }
        }
        return null;
    }


}