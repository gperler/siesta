<?php

namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\XMLIndexPart;

/**
 * Class IndexPart
 * @package siestaphp\datamodel\index
 */
class IndexPart implements Processable, IndexPartSource
{

    const VALIDATION_ERROR_INVALID_NAME = 500;

    const VALIDATION_ERROR_INVALID_COLUMN = 501;
    /**
     * @var EntitySource
     */
    protected $entity;

    /**
     * @var Index
     */
    protected $index;

    /**
     * @var IndexPartSource
     */
    protected $indexPartSource;

    /**
     * @var bool
     */
    protected $columnExists;

    /**
     * @param Entity $entity
     * @param Index $index
     * @param IndexPartSource $indexPartSource
     */
    public function __construct(Entity $entity, Index $index, IndexPartSource $indexPartSource)
    {
        $this->entity = $entity;
        $this->index = $index;
        $this->indexPartSource = $indexPartSource;
        $this->columnExists = false;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->indexPartSource->getColumnName();
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->indexPartSource->getSortOrder();
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return (int) $this->indexPartSource->getLength();
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container)
    {
        $this->checkIfIndexPartIsForAttribute();
        $this->checkIfIndexPartIsForReference();

    }

    /**
     * checks if the index part refers an attribute of the entity
     * @return void
     */
    private function checkIfIndexPartIsForAttribute()
    {
        foreach($this->entity->getAttributeSourceList() as $attribute) {
            if ($attribute->getDatabaseName() === $this->getColumnName()) {
                $this->columnExists = true;
                return;
            }
        }
    }

    /**
     * checks if the index part refers a reference of the entity
     * @return void
     */
    private function checkIfIndexPartIsForReference()
    {


        $reference = $this->entity->getReferenceByColumnName($this->getColumnName());
        if (!$reference) {
            return;
        }

        // a reference might have several referenced columns
        $referencedColumns = $reference->getReferencedColumn($this->getColumnName());
        if (!$referencedColumns) {
            return;
        }

        $this->columnExists = true;
    }

    /**
     * @param ValidationLogger $logger
     *
*@return void
     */
    public function validate(ValidationLogger $logger)
    {
        $logger->errorIfAttributeNotSet($this->getColumnName(), XMLIndexPart::ATTRIBUTE_COLUMN_NAME, XMLIndexPart::ELEMENT_INDEX_PART_NAME, self::VALIDATION_ERROR_INVALID_NAME);

        if (!$this->columnExists) {
            $logger->error("IndexPart " . $this->getColumnName() . " from index " . $this->index->getName() . " does not refer an existing attribute or reference", self::VALIDATION_ERROR_INVALID_COLUMN);
        }
    }

}