<?php

declare(strict_types = 1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class ReferenceMapping
{

    /**
     * @var DataModel
     */
    protected $dataModel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var string
     */
    protected $localAttributeName;

    /**
     * @var string
     */
    protected $foreignAttributeName;

    /**
     * @var Attribute
     */
    protected $localAttribute;

    /**
     * @var Attribute
     */
    protected $foreignAttribute;

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
    }

    /**
     * @param Entity $foreignEntity
     */
    public function update(Entity $foreignEntity)
    {
        $this->localAttribute = $this->entity->getAttributeByName($this->getLocalAttributeName());
        $this->foreignAttribute = $foreignEntity->getAttributeByName($this->getForeignAttributeName());
    }

    /**
     * @return string
     */
    public function getLocalAttributeName()
    {
        return $this->localAttributeName;
    }

    /**
     * @return string
     */
    public function getLocalColumnName()
    {
        return $this->localAttribute->getDBName();
    }

    /**
     * @param string $localAttributeName
     */
    public function setLocalAttributeName(string $localAttributeName = null)
    {
        $this->localAttributeName = $localAttributeName;
    }

    /**
     * @return string
     */
    public function getForeignAttributeName()
    {
        return $this->foreignAttributeName;
    }

    /**
     * @return string
     */
    public function getForeignColumnName()
    {
        return $this->foreignAttribute->getDBName();
    }

    /**
     * @param string $foreignAttributeName
     */
    public function setForeignAttributeName(string $foreignAttributeName = null)
    {
        $this->foreignAttributeName = $foreignAttributeName;
    }

    /**
     * @return Attribute|null
     */
    public function getLocalAttribute()
    {
        return $this->localAttribute;
    }

    /**
     * @return Attribute|null
     */
    public function getForeignAttribute()
    {
        return $this->foreignAttribute;
    }

}