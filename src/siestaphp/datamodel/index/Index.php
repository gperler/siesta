<?php

namespace siestaphp\datamodel\index;

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
        $this->indexPartList = [];
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
        if (!empty($indexName)) {
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
        $type = $this->indexSource->getType();
        if (!$type) {
            return 'BTREE';
        }
        return $type;
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
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(ValidationLogger $logger)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->validate($logger);
        }
    }

}