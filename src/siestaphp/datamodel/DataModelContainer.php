<?php

namespace siestaphp\datamodel;

use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\generator\ValidationLogger;
use siestaphp\util\Util;

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
     * @var ValidationLogger
     */
    protected $generatorLog;

    /**
     * @param ValidationLogger $validationLogger
     */
    public function __construct(ValidationLogger $validationLogger)
    {
        $this->entityList = array();

        $this->generatorLog = $validationLogger;
    }

    /**
     * @param EntitySource[] $sourceList
     *
     * @return void
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
     *
     * @return void
     */
    private function addEntitySource(EntitySource $source)
    {

        $entityClassName = $source->getClassName();

        if (empty($entityClassName)) {
            $this->generatorLog->warn("Found Entity without name in file(s) ", 0);
            return;
        }

        if (isset($this->entityList[$entityClassName])) {
            $existingEntity = $this->entityList[$entityClassName];
            $this->generatorLog->warn("Found Entity in 2 file(s) ignoring 2nd file", 0);
            return;
        }

        $entity = new Entity();
        $entity->setSource($source);

        $this->entityList[$entityClassName] = $entity;
    }

    /**
     * @return void
     */
    public function updateModel()
    {
        foreach ($this->entityList as $entity) {
            $entity->updateModel($this);
        }
    }

    /**
     * @return void
     */
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
    public function getEntityByClassname($entityName)
    {
        return Util::getFromArray($this->entityList, $entityName);
    }

    /**
     * @return EntityGeneratorSource[]
     */
    public function getEntityList()
    {
        return $this->entityList;
    }

}