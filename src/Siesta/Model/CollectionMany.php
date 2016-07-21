<?php
declare(strict_types = 1);

namespace Siesta\Model;

use Siesta\XML\XMLCollectionMany;

/**
 * @author Gregor Müller
 */
class CollectionMany
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
    protected $mappingTable;

    /**
     * @var Entity
     */
    protected $foreignEntity;

    /**
     * @var Reference
     */
    protected $foreignReference;

    /**
     * @var Entity
     */
    protected $mappingEntity;

    /**
     * @var Reference
     */
    protected $mappingReference;

    /**
     * CollectionMany constructor.
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
     * @param XMLCollectionMany $xmlCollectionMany
     */
    public function fromXMLCollectionMany(XMLCollectionMany $xmlCollectionMany)
    {
        $this->setName($xmlCollectionMany->getName());
        $this->setForeignTable($xmlCollectionMany->getForeignTable());
        $this->setMappingTable($xmlCollectionMany->getMappingTable());
    }

    /**
     *
     */
    public function update()
    {
        $this->foreignEntity = $this->datamodel->getEntityByTableName($this->getForeignTable());
        if ($this->foreignEntity !== null) {
            $this->foreignEntity->addForeignCollectionManyList($this);
        }

        $this->mappingEntity = $this->datamodel->getEntityByTableName($this->getMappingTable());

        if ($this->mappingEntity === null || $this->foreignEntity === null) {
            return;
        }

        foreach ($this->mappingEntity->getReferenceList() as $reference) {
            if ($reference->getForeignTable() === $this->foreignEntity->getTableName()) {
                $this->foreignReference = $reference;
            }
            if ($reference->getForeignTable() === $this->entity->getTableName()) {
                $this->mappingReference = $reference;
            }
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
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->getName());
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
    public function getMappingTable()
    {
        return $this->mappingTable;
    }

    /**
     * @param string $mappingTable
     */
    public function setMappingTable($mappingTable)
    {
        $this->mappingTable = $mappingTable;
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

    /**
     * @return Entity|null
     */
    public function getMappingEntity()
    {
        return $this->mappingEntity;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @return Reference
     */
    public function getMappingReference()
    {
        return $this->mappingReference;
    }

}