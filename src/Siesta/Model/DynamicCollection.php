<?php

declare(strict_types=1);

namespace Siesta\Model;

class DynamicCollection
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
    protected $name;

    /**
     * @var string
     */
    protected $foreignTable;

    /**
     * @var Entity
     */
    protected $foreignEntity;

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
    }

    /**
     *
     */
    public function update()
    {
        $this->foreignEntity = $this->dataModel->getEntityByTableName($this->getForeignTable());
        if ($this->foreignEntity) {
            $this->foreignEntity->setIsDynamicCollectionTarget();
        }
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->getName());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getForeignTable(): string
    {
        return $this->foreignTable;
    }

    /**
     * @param string $foreignTable
     */
    public function setForeignTable(string $foreignTable)
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return Entity
     */
    public function getForeignEntity()
    {
        return $this->foreignEntity;
    }

}