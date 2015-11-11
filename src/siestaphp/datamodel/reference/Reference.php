<?php

namespace siestaphp\datamodel\reference;

use siestaphp\datamodel\collector\CollectorFilter;
use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\generator\ValidationLogger;
use siestaphp\naming\StoredProcedureNaming;
use siestaphp\naming\XMLReference;

/**
 * Class Reference
 * @package siestaphp\datamodel
 */
class Reference implements Processable, ReferenceSource, ReferenceGeneratorSource
{

    const VALIDATION_ERROR_INVALID_REFERENCE_NAME = 300;

    const VALIDATION_ERROR_INVALID_ON_DELETE = 302;

    const VALIDATION_ERROR_INVALID_ON_UPDATE = 303;

    const VALIDATION_ERROR_ON_DELETE_NULL_AND_REQUIRED = 304;

    const VALIDATION_ERROR_REFERENCED_ENTITY_NOT_FOUND = 305;

    private static $ALLOWED_ON_X = array("restrict", "cascade", "set null", "no action");

    const PARAMETER_PREFIX = "P_";

    const ON_X_RESTRICT = "restrict";

    const ON_X_CASCADE = "cascade";

    const ON_X_SET_NULL = "set null";

    const ON_X_NONE = "no action";

    /**
     * @var ReferenceSource
     */
    protected $referenceSource;

    /**
     * @var EntitySource
     */
    protected $entitySource;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $relationName;

    /**
     * @var string
     */
    protected $foreignMethodName;

    /**
     * @var string
     */
    protected $foreignClass;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var string
     */
    protected $onDelete;

    /**
     * @var string
     */
    protected $onUpdate;

    /**
     * @var bool
     */
    protected $isPrimaryKey;

    /* derived data from here */

    /**
     * @var Entity
     */
    protected $referencedEntity;

    /**
     * @var ReferencedColumnSource[]
     */
    protected $referenceColumnList;

    /**
     * @var CollectorFilter[]
     */
    protected $collectorFilterList;

    /**
     * @var bool
     */
    protected $referenceCreatorNeeded;

    /**
     * @param EntitySource $entitySource
     * @param ReferenceSource $source
     */
    public function setSource(EntitySource $entitySource, ReferenceSource $source)
    {
        $this->referenceCreatorNeeded = false;

        $this->entitySource = $entitySource;

        $this->referenceSource = $source;

        $this->referenceColumnList = array();

        $this->collectorFilterList = array();

        $this->storeReferenceData();

    }

    /**
     * @return void
     */
    private function storeReferenceData()
    {
        $this->name = $this->referenceSource->getName();
        $this->relationName = $this->referenceSource->getRelationName();
        $this->foreignClass = $this->referenceSource->getForeignClass();
        $this->required = $this->referenceSource->isRequired();
        $this->onDelete = $this->referenceSource->getOnDelete();
        $this->onUpdate = $this->referenceSource->getOnUpdate();
        $this->isPrimaryKey = $this->referenceSource->isPrimaryKey();
    }

    /**
     * @return string
     */
    public function getReferencedFullyQualifiedClassName()
    {
        if ($this->referencedEntity) {
            return $this->referencedEntity->getFullyQualifiedClassName();
        }
        return "";
    }

    /**
     * @param DataModelContainer $container
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container)
    {

        if (!$this->onUpdate) {
            $this->onUpdate = self::ON_X_NONE;
        }

        if (!$this->onDelete) {
            $this->onDelete = self::ON_X_NONE;
        }

        // try to find the referenced entity
        $this->referencedEntity = $container->getEntityByClassname($this->foreignClass);
        if (!$this->referencedEntity) {
            return;
        }

        // check if there is a bidrectional reference
        $reference = $this->referencedEntity->getReferenceByRelationName($this->relationName);
        if ($reference) {
            $reference->setReferenceCreatorNeeded();
            $this->foreignMethodName = $reference->getMethodName();
        }

        // build list with referenced columns
        $this->referenceColumnList = array();
        // no mappings > use the primary key columns
        if (sizeof($this->getMappingSourceList()) === 0) {
            $this->updateReferencedColumnFromPK();
        } else {
            $this->updateReferencedColumnFromMapping();
        }

    }

    private function updateReferencedColumnFromMapping()
    {
        foreach ($this->getMappingSourceList() as $mapping) {
            foreach ($this->referencedEntity->getAttributeSourceList() as $attribute) {
                if ($mapping->getForeignName() === $attribute->getName()) {
                    $referencedSource = new ReferencedColumn();
                    $referencedSource->fromAttributeSource($attribute, $this, $mapping);
                    $this->referenceColumnList[] = $referencedSource;
                }
            }
        }
    }

    private function updateReferencedColumnFromPK()
    {
        $pkAttributeList = $this->referencedEntity->getPrimaryKeyAttributeList();

        foreach ($pkAttributeList as $pkAttribute) {
            $mapping = $this->getMappingByForeignName($pkAttribute->getName());
            $referencedSource = new ReferencedColumn();
            $referencedSource->fromAttributeSource($pkAttribute, $this, $mapping);
            $this->referenceColumnList[] = $referencedSource;
        }
    }

    /**
     * @param $foreignName
     *
     * @return null|MappingSource
     */
    private function getMappingByForeignName($foreignName)
    {
        foreach ($this->referenceSource->getMappingSourceList() as $mapping) {
            if ($mapping->getForeignName() === $foreignName) {
                return $mapping;
            }
        }
        return null;
    }

    /**
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(ValidationLogger $logger)
    {
        $logger->errorIfAttributeNotSet($this->name, XMLReference::ATTRIBUTE_NAME, XMLReference::ELEMENT_REFERENCE_NAME, self::VALIDATION_ERROR_INVALID_REFERENCE_NAME);

        $logger->errorIfNotInList($this->onDelete, self::$ALLOWED_ON_X, XMLReference::ATTRIBUTE_ON_DELETE, XMLReference::ELEMENT_REFERENCE_NAME, self::VALIDATION_ERROR_INVALID_ON_DELETE);

        $logger->errorIfNotInList($this->onUpdate, self::$ALLOWED_ON_X, XMLReference::ATTRIBUTE_ON_UPDATE, XMLReference::ELEMENT_REFERENCE_NAME, self::VALIDATION_ERROR_INVALID_ON_UPDATE);

        if ($this->onDelete === self::ON_X_SET_NULL and $this->required) {
            $logger->error("Reference '" . $this->name . "' has required='true' but onDelete='setnull'. Either change onDelete or required", self::VALIDATION_ERROR_ON_DELETE_NULL_AND_REQUIRED);
        }

        if (!$this->referencedEntity) {
            $logger->error("Reference '" . $this->name . "' refers to unknown entity " . $this->foreignClass, self::VALIDATION_ERROR_REFERENCED_ENTITY_NOT_FOUND);
        }


    }

    /**
     * indicates that this reference needs to be backlinked
     * @return void
     */
    public function setReferenceCreatorNeeded()
    {
        $this->referenceCreatorNeeded = true;
    }

    /**
     * @return bool
     */
    public function isReferenceCreatorNeeded()
    {
        return $this->referenceCreatorNeeded;
    }

    /**
     * @return ReferencedColumnSource[]
     */
    public function getReferencedColumnList()
    {
        return $this->referenceColumnList;
    }



    /**
     * @param string $columnName
     *
     * @return ReferencedColumnSource
     */
    public function getReferencedColumn($columnName)
    {
        foreach ($this->referenceColumnList as $column) {
            if ($column->getDatabaseName() === $columnName) {
                return $column;
            }
        }
        return null;
    }

    /**
     * @return MappingSource[]
     */
    public function getMappingSourceList()
    {
        return $this->referenceSource->getMappingSourceList();
    }

    /**
     * @param CollectorFilter[] $collectorFilterList
     */
    public function addCollectorFilter(array $collectorFilterList) {
        $this->collectorFilterList = array_merge($this->collectorFilterList, $collectorFilterList);
    }

    /**
     * @return CollectorFilterSource[]
     */
    public function getCollectorFilterSourceList() {
        return $this->collectorFilterList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getConstraintName()
    {
        if ($this->referenceSource->getConstraintName()) {
            return $this->referenceSource->getConstraintName();
        }
        return $this->entitySource->getTable() . "_" . $this->name;
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        return $this->relationName;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStoredProcedureFinderName()
    {
        return StoredProcedureNaming::getSPFindByReferenceName($this->entitySource->getTable(), $this->getDatabaseName());
    }

    /**
     * @return string
     */
    public function getStoredProcedureDeleterName()
    {
        return StoredProcedureNaming::getSPDeleteByReferenceName($this->entitySource->getTable(), $this->getDatabaseName());
    }

    /**
     * @return string
     */
    public function getForeignClass()
    {
        return $this->referencedEntity->getClassName();
    }

    /**
     * @return string
     */
    public function getForeignTable()
    {
        if ($this->referencedEntity) {
            return $this->referencedEntity->getTable();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getForeignMethodName()
    {
        return $this->foreignMethodName;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    public function getReferencedConstructClass()
    {
        return $this->referencedEntity->getConstructorClass();
    }

    /**
     * @return string
     */
    public function getReferencedTableName()
    {
        return $this->referencedEntity->getTable();
    }

    /**
     * @return string
     */
    public function getOnDelete()
    {
        return $this->onDelete;
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return $this->onUpdate;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

}