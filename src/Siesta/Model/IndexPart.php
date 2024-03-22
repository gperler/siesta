<?php
declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class IndexPart
{

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string|null
     */
    protected ?string $columnName;

    /**
     * @var string|null
     */
    protected ?string $sortOrder;

    /**
     * @var int|null
     */
    protected ?int $length;

    /**
     * @var Attribute|null
     */
    protected ?Attribute $attribute;

    /**
     * @var string|null
     */
    protected ?string $attributeName;

    /**
     * IndexPart constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->columnName = null;
        $this->sortOrder = null;
        $this->length = null;
        $this->attribute = null;
        $this->attributeName = null;
    }

    public function update(): void
    {
        $this->attribute = $this->entity->getAttributeByName($this->getAttributeName());
    }

    public function validate()
    {

    }

    /**
     * @return Attribute|null
     */
    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    /**
     * @return string|null
     */
    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    /**
     * @param string|null $attributeName
     */
    public function setAttributeName(string $attributeName = null): void
    {
        $this->attributeName = $attributeName;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->attribute->getDBName();
    }

    /**
     * @param string|null $columnName
     */
    public function setColumnName(?string $columnName): void
    {
        $this->columnName = $columnName;
    }

    /**
     * @return string|null
     */
    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    /**
     * @param string|null $sortOrder
     */
    public function setSortOrder(string $sortOrder = null): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|null $length
     */
    public function setLength(int $length = null): void
    {
        $this->length = $length;
    }

}