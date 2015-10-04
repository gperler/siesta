<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.09.15
 * Time: 11:38
 */

namespace siestaphp\datamodel\collector;

use Codeception\Util\Debug;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\datamodel\reference\Reference;
use siestaphp\generator\GeneratorLog;


/**
 * Class Collector
 * @package siestaphp\datamodel
 */
class Collector implements Processable, CollectorSource, CollectorTransformerSource
{

    /**
     * @var CollectorSource
     */
    protected $collectorSource;


    /**
     * @var Entity
     */
    protected $foreignClassEntity;


    /**
     * @var Reference
     */
    protected $reference;

    /**
     *
     * @param CollectorSource $source
     */
    public function setSource(CollectorSource $source)
    {
        $this->collectorSource = $source;
    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        $this->foreignClassEntity = $container->getEntityDetails($this->getForeignClass());

        if ($this->foreignClassEntity) {
            $this->reference = $this->foreignClassEntity->getReferenceByName($this->getReferenceName());
        }


    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {
        if (!$this->foreignClassEntity) {
            $log->error("Collector '" . $this->getName() . "' refers to unknown entity " . $this->getForeignClass());
        }

        if (!$this->reference) {
            $log->error("Collector '" . $this->getName() . "' refers to unknown reference " . $this->getReferenceName());

        }

    }

    /**
     * @return string
     */
    public function getReferencedFullyQualifiedClassName()
    {
        if ($this->foreignClassEntity) {
            return $this->foreignClassEntity->getFullyQualifiedClassName();
        }
        return "";
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->collectorSource->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->collectorSource->getType();
    }

    /**
     * @return string
     */
    public function getForeignClass()
    {
        return $this->collectorSource->getForeignClass();
    }

    /**
     * @return string
     */
    public function getReferenceName()
    {
        return $this->collectorSource->getReferenceName();
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->collectorSource->getName());
    }


    /**
     * @return string
     */
    public function getForeignConstructClass()
    {
        return $this->foreignClassEntity->getConstructorClass();
    }


    /**
     * @return string
     */
    public function getReferenceMethodName()
    {
        return ucfirst($this->getReferenceName());
    }

}