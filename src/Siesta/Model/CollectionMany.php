<?php
declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class CollectionMany
{

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $foreignTable;

    /**
     * @var string|null
     */
    protected ?string $mappingTable;

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
     * @var Reference|null
     */
    protected ?Reference $mappingReference;

    /**
     * CollectionMany constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->name = null;
        $this->foreignTable = null;
        $this->mappingTable = null;
        $this->foreignEntity = null;
        $this->foreignReference = null;
        $this->mappingEntity = null;
        $this->mappingReference = null;
    }

    /**
     *
     */
    public function update(): void
    {
        $this->updateForeignEntity();

        $this->updateMappingEntity();

        $this->updateReferences();
    }

    /**
     *
     */
    protected function updateForeignEntity(): void
    {
        $this->foreignEntity = $this->dataModel->getEntityByTableName($this->getForeignTable());
        if ($this->foreignEntity === null) {
            return;
        }
        $this->foreignEntity->addForeignCollectionManyList($this);
    }

    /**
     *
     */
    protected function updateMappingEntity(): void
    {
        $this->mappingEntity = $this->dataModel->getEntityByTableName($this->getMappingTable());
    }

    /**
     *
     */
    protected function updateReferences(): void
    {
        if ($this->mappingEntity === null || $this->foreignEntity === null) {
            return;
        }

        foreach ($this->mappingEntity->getReferenceList() as $reference) {
            if ($reference->getForeignTable() === $this->foreignEntity->getTableName()) {
                $this->foreignReference = $reference;
            }
        }

        foreach ($this->mappingEntity->getReferenceList() as $reference) {
            if ($reference->getForeignTable() === $this->entity->getTableName() && $this->foreignReference !== null && $reference->getName() !== $this->foreignReference->getName()) {
                $this->mappingReference = $reference;
            }
        }

    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ucfirst($this->getName());
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getForeignTable(): ?string
    {
        return $this->foreignTable;
    }

    /**
     * @param string|null $foreignTable
     */
    public function setForeignTable(?string $foreignTable): void
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string|null
     */
    public function getMappingTable(): ?string
    {
        return $this->mappingTable;
    }

    /**
     * @param string|null $mappingTable
     */
    public function setMappingTable(?string $mappingTable): void
    {
        $this->mappingTable = $mappingTable;
    }

    /**
     * @return Entity|null
     */
    public function getForeignEntity(): ?Entity
    {
        return $this->foreignEntity;
    }

    /**
     * @return Reference|null
     */
    public function getForeignReference(): ?Reference
    {
        return $this->foreignReference;
    }

    /**
     * @return Entity|null
     */
    public function getMappingEntity(): ?Entity
    {
        return $this->mappingEntity;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @return Reference|null
     */
    public function getMappingReference(): ?Reference
    {
        return $this->mappingReference;
    }

}