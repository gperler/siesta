<?php
declare(strict_types = 1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class IndexPart
{

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var string
     */
    protected $columnName;

    /**
     * @var string
     */
    protected $sortOrder;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * IndexPart constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function update()
    {
        $this->attribute = $this->entity->getAttributeByDbName($this->getColumnName());
    }

    public function validate()
    {

    }

    /**
     * @return Attribute|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

}