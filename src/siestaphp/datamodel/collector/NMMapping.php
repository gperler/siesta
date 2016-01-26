<?php

namespace siestaphp\datamodel\collector;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class NMMapping
 * @package siestaphp\datamodel\entity
 */
class NMMapping implements NMMappingSource
{

    /**
     * @var EntityGeneratorSource
     */
    public $entity;

    /**
     * @var EntityGeneratorSource
     */
    public $foreignEntity;

    /**
     * @var EntityGeneratorSource
     */
    public $mappingEntity;

    /**
     * @var CollectorGeneratorSource
     */
    public $collector;

    /**
     * @return string
     */
    public function getPHPMethodName()
    {
        return "get" . $this->entity->getClassName() . "Join" . $this->mappingEntity->getClassName();
    }

    /**
     * @return DatabaseColumn[]
     */
    public function getForeignPrimaryKeyColumnList()
    {
        return $this->foreignEntity->getPrimaryKeyColumns();
    }

    /**
     * @return string
     */
    public function getStoredProcedureName()
    {
        return StoredProcedureNaming::getSPJoinCollectorNMName($this);
    }

    /**
     * @return string
     */
    public function getDeleteStoredProcedureName()
    {
        return StoredProcedureNaming::getSPDeleteNMName($this);
    }

}