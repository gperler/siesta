<?php

namespace siestaphp\datamodel\reference;

use Codeception\Util\Debug;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\generator\GeneratorLog;
use siestaphp\naming\StoredProcedureNaming;
use siestaphp\naming\XMLReference;

/**
 * Class Reference
 * @package siestaphp\datamodel
 */
class Reference implements Processable, ReferenceSource, ReferenceTransformerSource, ReferenceDatabaseSource
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

        $this->storeReferenceData();

    }

    private function storeReferenceData()
    {
        $this->name = $this->referenceSource->getName();
        $this->relationName = $this->referenceSource->getRelationName();
        $this->foreignClass = $this->referenceSource->getForeignClass();
        $this->required = $this->referenceSource->isRequired();
        $this->onDelete = $this->referenceSource->getOnDelete();
        $this->onUpdate = $this->referenceSource->getOnUpdate();
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
        $this->referencedEntity = $container->getEntityDetails($this->foreignClass);
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
        $pkAttributeList = $this->referencedEntity->getPrimaryKeyAttributeList();

        foreach ($pkAttributeList as $pkAttribute) {
            $referencedSource = new ReferencedColumn();
            $referencedSource->fromAttributeSource($pkAttribute, $this);
            $this->referenceColumnList[] = $referencedSource;
        }

    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {
        $log->errorIfAttributeNotSet($this->name, XMLReference::ATTRIBUTE_NAME, XMLReference::ELEMENT_REFERENCE_NAME, self::VALIDATION_ERROR_INVALID_REFERENCE_NAME);

        $log->errorIfNotInList($this->onDelete, self::$ALLOWED_ON_X, XMLReference::ATTRIBUTE_ON_DELETE, XMLReference::ELEMENT_REFERENCE_NAME, self::VALIDATION_ERROR_INVALID_ON_DELETE);

        $log->errorIfNotInList($this->onUpdate, self::$ALLOWED_ON_X, XMLReference::ATTRIBUTE_ON_UPDATE, XMLReference::ELEMENT_REFERENCE_NAME, self::VALIDATION_ERROR_INVALID_ON_UPDATE);

        if ($this->onDelete === self::ON_X_SET_NULL and $this->required) {
            $log->error("Reference '" . $this->name . "' has required='true' but onDelete='setnull'. Either change onDelete or required", self::VALIDATION_ERROR_ON_DELETE_NULL_AND_REQUIRED);
        }

        if (!$this->referencedEntity) {
            $log->error("Reference '" . $this->name . "' refers to unknown entity " . $this->foreignClass, self::VALIDATION_ERROR_REFERENCED_ENTITY_NOT_FOUND);
        }
    }

    /**
     * indicates that this reference needs to be backlinked
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
    public function getReferenceColumnList()
    {
        return $this->referenceColumnList;
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
    public function getRelationName()
    {
        return $this->relationName;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return strtoupper($this->name);
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
        return $this->foreignClass;
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

}