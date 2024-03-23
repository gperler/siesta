<?php
declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class Collection
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
    protected ?string $foreignReferenceName;

    /**
     * @var Entity|null
     */
    protected ?Entity $foreignEntity;

    /**
     * @var Reference|null
     */
    protected ?Reference $foreignReference;

    /**
     * Collection constructor.
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
        $this->foreignReferenceName = null;
        $this->foreignEntity = null;
        $this->foreignReference = null;
    }

    /**
     *
     */
    public function update(): void
    {
        $this->foreignEntity = $this->dataModel->getEntityByTableName($this->getForeignTable());
        if ($this->foreignEntity === null) {
            return;
        }
        $this->foreignReference = $this->foreignEntity->getReferenceByName($this->getForeignReferenceName());
        if ($this->foreignReference !== null) {
            $this->foreignReference->setCollectionRefersTo();
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
     * @param string|null $name
     */
    public function setName(string $name = null): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ucfirst($this->getName());
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
    public function setForeignTable(string $foreignTable = null): void
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string|null
     */
    public function getForeignReferenceName(): ?string
    {
        return $this->foreignReferenceName;
    }

    /**
     * @param string|null $foreignReferenceName
     */
    public function setForeignReferenceName(string $foreignReferenceName = null): void
    {
        $this->foreignReferenceName = $foreignReferenceName;
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

}