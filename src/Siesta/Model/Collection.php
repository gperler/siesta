<?php
declare(strict_types = 1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class Collection
{
    /**
     * @var DataModel
     */
    protected $datamodel;

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
     * @var string
     */
    protected $foreignReferenceName;

    /**
     * @var Entity
     */
    protected $foreignEntity;

    /**
     * @var Reference
     */
    protected $foreignReference;

    /**
     * Collection constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        $this->datamodel = $dataModel;
        $this->entity = $entity;
    }

    /**
     *
     */
    public function update()
    {
        $this->foreignEntity = $this->datamodel->getEntityByTableName($this->getForeignTable());
        if ($this->foreignEntity === null) {
            return;
        }
        $this->foreignReference = $this->foreignEntity->getReferenceByName($this->getForeignReferenceName());
        if ($this->foreignReference !== null) {
            $this->foreignReference->setCollectionRefersTo();
        }

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    /**
     * @param string $foreignTable
     */
    public function setForeignTable($foreignTable)
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string
     */
    public function getForeignReferenceName()
    {
        return $this->foreignReferenceName;
    }

    /**
     * @param string $foreignReferenceName
     */
    public function setForeignReferenceName($foreignReferenceName)
    {
        $this->foreignReferenceName = $foreignReferenceName;
    }

    /**
     * @return Entity|null
     */
    public function getForeignEntity()
    {
        return $this->foreignEntity;
    }

    /**
     * @return Reference|null
     */
    public function getForeignReference()
    {
        return $this->foreignReference;
    }

}