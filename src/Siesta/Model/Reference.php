<?php
declare(strict_types = 1);

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
    protected $constraintName;

    /**
     * @var string
     */
    protected $foreignTable;

    /**
     * @var string
     */
    protected $onDelete;

    /**
     * @var string
     */
    protected $onUpdate;

    /**
     * @var bool
     */
    protected $noConstraint;

    /**
     * @var bool
     */
    protected $collectionRefersTo;

    /**
     * @var ReferenceMapping[]
     */
    protected $referenceMappingList;

    // from here derived

    /**
     * @var Entity
     */
    protected $foreignEntity;

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
        $this->referenceMappingList = [];
    }

    /**
     * @return ReferenceMapping
     */
    public function newReferenceMapping() : ReferenceMapping
    {
        $referenceMapping = new ReferenceMapping($this->dataModel, $this->entity);
        $this->referenceMappingList[] = $referenceMapping;
        return $referenceMapping;
    }

    /**
     *
     */
    public function update()
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
    public function getConstraintName() : string
    {
        if ($this->constraintName !== null) {
            return $this->constraintName;
        }
        return $this->entity->getTableName() . "_" . $this->getName();
    }

    /**
     * @param string $constraintName
     */
    public function setConstraintName(string $constraintName = null)
    {
        $this->constraintName = $constraintName;
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
    public function setName(string $name = null)
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
    public function setForeignTable(string $foreignTable = null)
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string
     */
    public function getOnDelete()
    {
        return $this->onDelete !== null ? $this->onDelete : "restrict";
    }

    /**
     * @param string $onDelete
     */
    public function setOnDelete(string $onDelete = null)
    {
        $this->onDelete = $onDelete;
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return $this->onUpdate !== null ? $this->onUpdate : "restrict";
    }

    /**
     * @param string $onUpdate
     */
    public function setOnUpdate(string $onUpdate = null)
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
    public function setNoConstraint(bool $noConstraint)
    {
        $this->noConstraint = $noConstraint;
    }

    /**
     * @return ReferenceMapping[]
     */
    public function getReferenceMappingList()
    {
        return $this->referenceMappingList;
    }

    /**
     * @return Entity
     */
    public function getForeignEntity()
    {
        return $this->foreignEntity;
    }

    /**
     * @return bool
     */
    public function doesCollectionRefersTo()
    {
        return $this->collectionRefersTo;
    }

    /**
     *
     */
    public function setCollectionRefersTo()
    {
        $this->collectionRefersTo = true;
    }

}