<?php


namespace siestaphp\datamodel\index;

use Codeception\Util\Debug;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;

/**
 * Class Index
 * @package siestaphp\datamodel\index
 */
class Index implements Processable, IndexSource, IndexGeneratorSource
{

    /**
     * @var IndexSource
     */
    protected $indexSource;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var IndexPart[]
     */
    protected $indexPartList;

    /**
     * @param Entity $entity
     * @param IndexSource $indexSource
     */
    public function __construct(Entity $entity, IndexSource $indexSource)
    {
        $this->indexSource = $indexSource;
        $this->entity = $entity;
        $this->extractIndexPartList();
    }

    private function extractIndexPartList()
    {
        $this->indexPartList = array();
        foreach ($this->indexSource->getIndexPartSourceList() as $indexPartSource) {
            $this->indexPartList[] = new IndexPart($this->entity, $this, $indexPartSource);
        }
    }

    /**
     * @return IndexPartGeneratorSource[]
     */
    public function getIndexPartGeneratorSourceList() {
        return $this->indexPartList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->indexSource->getName();
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->indexSource->isUnique();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->indexSource->getType();
    }

    /**
     * @return IndexPartSource[]
     */
    public function getIndexPartSourceList()
    {
        return $this->indexPartList;
    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->updateModel($container);
        }
    }

    /**
     * @param ValidationLogger $log
     */
    public function validate(ValidationLogger $log)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->validate($log);
        }
    }

}