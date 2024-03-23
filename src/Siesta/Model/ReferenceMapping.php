<?php

declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class ReferenceMapping
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
    protected ?string $localAttributeName;

    /**
     * @var string|null
     */
    protected ?string $foreignAttributeName;

    /**
     * @var Attribute|null
     */
    protected ?Attribute $localAttribute;

    /**
     * @var Attribute|null
     */
    protected ?Attribute $foreignAttribute;

    /**
     * ReferenceMapping constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->localAttributeName = null;
        $this->foreignAttributeName = null;
        $this->localAttribute = null;
        $this->foreignAttribute = null;
    }

    /**
     * @param Entity $foreignEntity
     */
    public function update(Entity $foreignEntity): void
    {
        $this->localAttribute = $this->entity->getAttributeByName($this->getLocalAttributeName());
        $this->foreignAttribute = $foreignEntity->getAttributeByName($this->getForeignAttributeName());

        if ($this->localAttribute !== null) {
            $this->localAttribute->setIsForeignKey();
        }
    }

    /**
     * @return string|null
     */
    public function getLocalAttributeName(): ?string
    {
        return $this->localAttributeName;
    }

    /**
     * @return string
     */
    public function getLocalColumnName(): string
    {
        return $this->localAttribute->getDBName();
    }

    /**
     * @param string|null $localAttributeName
     */
    public function setLocalAttributeName(string $localAttributeName = null): void
    {
        $this->localAttributeName = $localAttributeName;
    }

    /**
     * @return string|null
     */
    public function getForeignAttributeName(): ?string
    {
        return $this->foreignAttributeName;
    }

    /**
     * @return string
     */
    public function getForeignColumnName(): string
    {
        return $this->foreignAttribute->getDBName();
    }

    /**
     * @param string|null $foreignAttributeName
     */
    public function setForeignAttributeName(string $foreignAttributeName = null): void
    {
        $this->foreignAttributeName = $foreignAttributeName;
    }

    /**
     * @return Attribute|null
     */
    public function getLocalAttribute(): ?Attribute
    {
        return $this->localAttribute;
    }

    /**
     * @return Attribute|null
     */
    public function getForeignAttribute(): ?Attribute
    {
        return $this->foreignAttribute;
    }

}