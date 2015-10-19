<?php

namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;

/**
 * Class Index
 * @package siestaphp\datamodel\index
 */
class Index implements Processable, IndexSource
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

    /**
     * @return void
     */
    private function extractIndexPartList()
    {
        $this->indexPartList = array();
        foreach ($this->indexSource->getIndexPartSourceList() as $indexPartSource) {
            $this->indexPartList[] = new IndexPart($this->entity, $this, $indexPartSource);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        $indexName = $this->indexSource->getName();
        if ($indexName) {
            return $indexName;
        }

        $indexName = $this->entity->getTable() . "_";
        foreach ($this->getIndexPartSourceList() as $indexPart) {
            $indexName .= $indexPart->getColumnName();
        }
        $indexName .= "_index";
        return $indexName;
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
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->updateModel($container);
        }
    }

    /**
     * @param ValidationLogger $log
     *
     * @return void
     */
    public function validate(ValidationLogger $log)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->validate($log);
        }
    }

}