<?php

declare(strict_types=1);

namespace Siesta\Model;

class DynamicCollection
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
     * @var Entity|null
     */
    protected ?Entity $foreignEntity;

    /**
     * DynamicCollection constructor.
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
        $this->foreignEntity = null;
    }

    /**
     *
     */
    public function update(): void
    {
        $this->foreignEntity = $this->dataModel->getEntityByTableName($this->getForeignTable());
        if ($this->foreignEntity) {
            $this->foreignEntity->setIsDynamicCollectionTarget();
        }
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
    public function getName(): ?string
    {
        return $this->name;
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
     * @return Entity|null
     */
    public function getForeignEntity(): ?Entity
    {
        return $this->foreignEntity;
    }

}