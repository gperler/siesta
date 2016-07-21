<?php
declare(strict_types = 1);

namespace Siesta\Model;

use Siesta\XML\XMLEntity;

/**
 * @author Gregor Müller
 */
class DataModel
{

    /**
     * @var Entity[]
     */
    protected $entityList;

    /**
     * DataModel constructor.
     */
    public function __construct()
    {
        $this->entityList = [];
    }

    /**
     * @param XMLEntity[] $xmlEntityList
     */
    public function addXMLEntityList(array $xmlEntityList)
    {
        foreach ($xmlEntityList as $xmlEntity) {
            $this->addXMLEntity($xmlEntity);
        }
    }

    public function addXMLEntity(XMLEntity $xmlEntity)
    {
        $dataModelBuilder = new XMLEntityReader();

        $entity = new Entity($this);
        $dataModelBuilder->getEntity($entity, $xmlEntity);

        $this->entityList[] = $entity;
    }

    /**
     * @param string $tableName
     *
     * @return bool
     */
    public function hasEntityByTableName(string $tableName)
    {
        foreach ($this->entityList as $entity) {
            if ($entity->getTableName() === $tableName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $tableName
     *
     * @return Entity
     */
    public function getEntityByTableName(string $tableName = null)
    {
        foreach ($this->entityList as $entity) {
            if ($entity->getTableName() === $tableName) {
                return $entity;
            }
        }

        return null;
    }

    public function update()
    {
        foreach ($this->entityList as $entity) {
            $entity->update();
        }
    }

    /**
     * @return Entity[]
     */
    public function getEntityList()
    {
        return $this->entityList;
    }

}