<?php

namespace siestaphp\datamodel;

use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\generator\GeneratorLog;

/**
 * Class DataModelContainer contains the entire defined data model
 * @package siestaphp\datamodel
 */
class DataModelContainer
{

    /**
     * @var Entity[]
     */
    protected $entityList;

    /**
     * @var GeneratorLog
     */
    protected $generatorLog;

    /**
     * @param $generatorLog
     */
    public function __construct($generatorLog)
    {
        $this->entityList = array();

        $this->generatorLog = $generatorLog;
    }

    /**
     * @param EntitySource[] $sourceList
     */
    public function addEntitySourceList($sourceList)
    {
        if ($sourceList === null) {
            return;
        }
        foreach ($sourceList as $source) {
            $this->addEntitySource($source);
        }
    }

    /**
     * @param EntitySource $source
     */
    private function addEntitySource(EntitySource $source)
    {

        $entityClassName = $source->getClassName();

        if (!$entityClassName) {
            $this->generatorLog->warn("Found Entity without name in file(s) ", 0);
            return;
        }

        if (isset($this->entityList[$entityClassName])) {
            $existingEntity = $this->entityList[$entityClassName];
            echo "Found Entity in 2 file(s)" . PHP_EOL;
            return;
        }

        $entity = new Entity();
        $entity->setSource($source);

        $this->entityList[$entityClassName] = $entity;
    }

    /**
     * @return bool
     */
    public function updateModel()
    {
        foreach ($this->entityList as $entity) {
            $entity->updateModel($this);
        }
    }

    public function validate()
    {
        foreach ($this->entityList as $entity) {
            $entity->validate($this->generatorLog);
        }
    }

    /**
     * @param $entityName
     *
     * @return Entity
     */
    public function getEntityDetails($entityName)
    {
        return $this->entityList[$entityName];
    }

    /**
     * @return EntityGeneratorSource[]
     */
    public function getEntityList()
    {
        return $this->entityList;
    }

}