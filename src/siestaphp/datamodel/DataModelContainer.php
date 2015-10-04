<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 23.06.15
 * Time: 15:21
 */

namespace siestaphp\datamodel;

use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\entity\EntityTransformerSource;
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
    protected $entityContainer;

    /**
     * @var GeneratorLog
     */
    protected $generatorLog;

    /**
     * @param $generatorLog
     */
    public function __construct($generatorLog)
    {
        $this->entityContainer = array();

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
            echo "Found Entity without name in file(s) " . PHP_EOL;
            return;
        }

        if (isset($this->entityContainer[$entityClassName])) {
            $existingEntity = $this->entityContainer[$entityClassName];
            echo "Found Entity in 2 file(s)" . PHP_EOL;
            return;
        }

        $entity = new Entity();
        $entity->setSource($source);

        $this->entityContainer[$entityClassName] = $entity;
    }

    /**
     * @return bool
     */
    public function updateModel()
    {
        foreach ($this->entityContainer as $entity) {
            $entity->updateModel($this);
        }
    }

    public function validate()
    {
        foreach ($this->entityContainer as $entity) {
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
        return $this->entityContainer[$entityName];
    }

    /**
     * @return EntityTransformerSource[]
     */
    public function getEntityList()
    {
        return $this->entityContainer;
    }

}