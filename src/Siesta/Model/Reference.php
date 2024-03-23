<?php
declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class Reference
{

    const ON_X_RESTRICT = "restrict";

    const ON_X_CASCADE = "cascade";

    const ON_X_SET_NULL = "set null";

    const ON_X_NONE = "no action";

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
    protected ?string $constraintName;

    /**
     * @var string|null
     */
    protected ?string $foreignTable;

    /**
     * @var string|null
     */
    protected ?string $onDelete;

    /**
     * @var string|null
     */
    protected ?string $onUpdate;

    /**
     * @var bool
     */
    protected bool $noConstraint;

    /**
     * @var bool
     */
    protected bool $collectionRefersTo;

    /**
     * @var ReferenceMapping[]
     */
    protected array $referenceMappingList;

    // from here derived

    /**
     * @var Entity|null
     */
    protected ?Entity $foreignEntity;

    /**
     * Reference constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->name = null;
        $this->constraintName = null;
        $this->foreignTable = null;
        $this->onDelete = null;
        $this->onUpdate = null;
        $this->noConstraint = false;
        $this->collectionRefersTo = false;
        $this->referenceMappingList = [];
        $this->foreignEntity = null;
    }

    /**
     * @return ReferenceMapping
     */
    public function newReferenceMapping(): ReferenceMapping
    {
        $referenceMapping = new ReferenceMapping($this->dataModel, $this->entity);
        $this->referenceMappingList[] = $referenceMapping;
        return $referenceMapping;
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

        foreach ($this->referenceMappingList as $referenceMapping) {
            $referenceMapping->update($this->foreignEntity);
        }

    }

    /**
     * @return string
     */
    public function getConstraintName(): string
    {
        if ($this->constraintName !== null) {
            return $this->constraintName;
        }
        return $this->entity->getTableName() . "_" . $this->getName();
    }

    /**
     * @param string|null $constraintName
     */
    public function setConstraintName(string $constraintName = null): void
    {
        $this->constraintName = $constraintName;
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
    public function getOnDelete(): ?string
    {
        return $this->onDelete !== null ? $this->onDelete : "restrict";
    }

    /**
     * @param string|null $onDelete
     */
    public function setOnDelete(string $onDelete = null): void
    {
        $this->onDelete = $onDelete;
    }

    /**
     * @return string|null
     */
    public function getOnUpdate(): ?string
    {
        return $this->onUpdate !== null ? $this->onUpdate : "restrict";
    }

    /**
     * @param string|null $onUpdate
     */
    public function setOnUpdate(string $onUpdate = null): void
    {
        $this->onUpdate = $onUpdate;
    }

    /**
     * @return boolean
     */
    public function getNoConstraint(): bool
    {
        return $this->noConstraint;
    }

    /**
     * @param boolean $noConstraint
     */
    public function setNoConstraint(bool $noConstraint): void
    {
        $this->noConstraint = $noConstraint;
    }

    /**
     * @return ReferenceMapping[]
     */
    public function getReferenceMappingList(): array
    {
        return $this->referenceMappingList;
    }

    /**
     * @return Entity|null
     */
    public function getForeignEntity(): ?Entity
    {
        return $this->foreignEntity;
    }

    /**
     * @return bool
     */
    public function doesCollectionRefersTo(): bool
    {
        return $this->collectionRefersTo;
    }

    /**
     *
     */
    public function setCollectionRefersTo(): void
    {
        $this->collectionRefersTo = true;
    }

}