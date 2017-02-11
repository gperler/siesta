<?php
declare(strict_types = 1);

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
     * @var string[]
     */
    protected $statementList;

    /**
     * @var string[]
     */
    protected $neededProcedureList;

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
     *
     * @param StoredProcedureFactory $factory
     */
    public function __construct(StoredProcedureFactory $factory)
    {
        $this->factory = $factory;
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

        $this->addSimpleStoredProcedurStatementList();
        $this->addCustomStoredProcedureStatementList();
        $this->addCollectionStoredProcedureList();
        $this->addCollectionManyStoredProcedureList();

        return $this->statementList;
    }

    /**
     *
     */
    protected function addSimpleStoredProcedurStatementList()
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
            $procedureDefiniton = $this->factory->createSelectByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addStatement($procedureDefiniton);

            $procedureDefiniton = $this->factory->createDeleteByReferenceStoredProcedure($this->dataModel, $this->entity, $reference);
            $this->addStatement($procedureDefiniton);
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

    /**
     * @param StoredProcedureDefinition $definition
     */
    protected function addStatement(StoredProcedureDefinition $definition)
    {
        $this->neededProcedureList[] = $definition->getProcedureName();

//        $dropDefinition = $definition->getDropProcedureStatement();
//        if ($dropDefinition !== null) {
//            $this->statementList[] = $dropDefinition;
//        }

        $createDefinition = $definition->getCreateProcedureStatement();
        if ($createDefinition !== null) {
            $this->statementList[] = $createDefinition;
        }

    }

    protected function createDropUnusedStatementList() {

    }

}