<?php

declare(strict_types=1);

namespace Siesta\Model;

use ReflectionException;
use Siesta\XML\XMLEntity;

/**
 * @author Gregor Müller
 */
class DataModel
{

    /**
     * @var Entity[]
     */
    protected array $entityList;


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
    public function addXMLEntityList(array $xmlEntityList): void
    {
        foreach ($xmlEntityList as $xmlEntity) {
            $this->addXMLEntity($xmlEntity);
        }
    }


    /**
     * @param XMLEntity $xmlEntity
     */
    public function addXMLEntity(XMLEntity $xmlEntity): void
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
    public function hasEntityByTableName(string $tableName): bool
    {
        foreach ($this->entityList as $entity) {
            if ($entity->getTableName() === $tableName) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param string|null $tableName
     *
     * @return Entity|null
     */
    public function getEntityByTableName(string $tableName = null): ?Entity
    {
        foreach ($this->entityList as $entity) {
            if ($entity->getTableName() === $tableName) {
                return $entity;
            }
        }

        return null;
    }


    /**
     * @throws ReflectionException
     */
    public function update(): void
    {
        foreach ($this->entityList as $entity) {
            $entity->update();
        }
    }


    /**
     * @return Entity[]
     */
    public function getEntityList(): array
    {
        return $this->entityList;
    }

}