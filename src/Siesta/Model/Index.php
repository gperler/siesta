<?php

declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class Index
{

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var bool
     */
    protected bool $isUnique;

    /**
     * @var string|null
     */
    protected ?string $indexType;

    /**
     * @var IndexPart[]
     */
    protected array $indexPartList;

    /**
     * Index constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->name = null;
        $this->isUnique = false;
        $this->indexType = null;
        $this->indexPartList = [];
    }

    /**
     * @return IndexPart
     */
    public function newIndexPart(): IndexPart
    {
        $indexPart = new IndexPart($this->entity);
        $this->indexPartList[] = $indexPart;
        return $indexPart;
    }

    /**
     *
     */
    public function update(): void
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->update();
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
     * @return bool
     */
    public function getIsUnique(): bool
    {
        return $this->isUnique;
    }

    /**
     * @param bool $isUnique
     */
    public function setIsUnique(bool $isUnique = null): void
    {
        $this->isUnique = $isUnique;
    }

    /**
     * @return string
     */
    public function getIndexType(): string
    {
        return $this->indexType ? strtolower($this->indexType) : 'btree';
    }

    /**
     * @param string|null $indexType
     */
    public function setIndexType(string $indexType = null): void
    {
        $this->indexType = $indexType;
    }

    /**
     * @return IndexPart[]
     */
    public function getIndexPartList(): array
    {
        return $this->indexPartList;
    }

}