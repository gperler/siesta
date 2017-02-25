<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Database\MetaData\ConstraintMetaData;

/**
 * @author Gregor Müller
 */
class XMLReference
{

    const ELEMENT_REFERENCE_NAME = "reference";

    const NAME = "name";

    const CONSTRAINT_NAME = "constraintName";

    const FOREIGN_TABLE = "foreignTable";

    const ON_DELETE = "onDelete";

    const ON_UPDATE = "onUpdate";

    const NO_CONSTRAINT = "noConstraint";

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $constraintName;

    /**
     * @var string
     */
    protected $foreignTable;

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
    protected $noConstraint;

    /**
     * @var XMLReferenceMapping[]
     */
    protected $xmlReferenceMappingList;

    public function __construct()
    {
        $this->xmlReferenceMappingList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setForeignTable($xmlAccess->getAttribute(self::FOREIGN_TABLE));
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setConstraintName($xmlAccess->getAttribute(self::CONSTRAINT_NAME));
        $this->setOnDelete($xmlAccess->getAttribute(self::ON_DELETE));
        $this->setOnUpdate($xmlAccess->getAttribute(self::ON_UPDATE));
        $this->setNoConstraint($xmlAccess->getAttributeAsBool(self::NO_CONSTRAINT));

        foreach ($xmlAccess->getXMLChildElementListByName(XMLReferenceMapping::ELEMENT_REFERENCE_MAPPING_NAME) as $referenceMappingXMLAccess) {
            $xmlReferenceMapping = new XMLReferenceMapping();
            $xmlReferenceMapping->fromXML($referenceMappingXMLAccess);
            $this->xmlReferenceMappingList[] = $xmlReferenceMapping;
        }

    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_REFERENCE_NAME);
        $xmlWrite->setAttribute(self::FOREIGN_TABLE, $this->getForeignTable());
        $xmlWrite->setAttribute(self::NAME, $this->getName());
        $xmlWrite->setAttribute(self::CONSTRAINT_NAME, $this->getConstraintName());
        $xmlWrite->setAttribute(self::ON_UPDATE, $this->getOnUpdate());
        $xmlWrite->setAttribute(self::ON_DELETE, $this->getOnDelete());
        $xmlWrite->setBoolAttribute(self::NO_CONSTRAINT, $this->getNoConstraint());
        foreach ($this->getXmlReferenceMappingList() as $xmlReferenceMapping) {
            $xmlReferenceMapping->toXML($xmlWrite);
        }
    }

    public function fromConstraintMetaData(ConstraintMetaData $constraintMetaData)
    {
        $this->setName($constraintMetaData->getName());
        $this->setConstraintName($constraintMetaData->getConstraintName());
        $this->setForeignTable($constraintMetaData->getForeignTable());
        $this->setOnDelete($constraintMetaData->getOnDelete());
        $this->setOnUpdate($constraintMetaData->getOnUpdate());
        $this->setNoConstraint(false);

        foreach ($constraintMetaData->getConstraintMappingList() as $constraintMappingMetaData) {
            $xmlReferenceMapping = new XMLReferenceMapping();
            $xmlReferenceMapping->fromConstraintMappingMetaData($constraintMappingMetaData);
            $this->xmlReferenceMappingList[] = $xmlReferenceMapping;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    /**
     * @param string $foreignTable
     */
    public function setForeignTable($foreignTable)
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string
     */
    public function getOnDelete()
    {
        return $this->onDelete;
    }

    /**
     * @param string $onDelete
     */
    public function setOnDelete($onDelete)
    {
        $this->onDelete = $onDelete;
    }

    /**
     * @return string
     */
    public function getOnUpdate()
    {
        return $this->onUpdate;
    }

    /**
     * @param string $onUpdate
     */
    public function setOnUpdate($onUpdate)
    {
        $this->onUpdate = $onUpdate;
    }

    /**
     * @return boolean
     */
    public function getNoConstraint(): bool
    {
        return $this->noConstraint;
    }

    /**
     * @param boolean $noConstraint
     */
    public function setNoConstraint(bool $noConstraint)
    {
        $this->noConstraint = $noConstraint;
    }

    /**
     * @return string
     */
    public function getConstraintName()
    {
        return $this->constraintName;
    }

    /**
     * @param string $constraintName
     */
    public function setConstraintName($constraintName)
    {
        $this->constraintName = $constraintName;
    }

    /**
     * @return XMLReferenceMapping[]
     */
    public function getXmlReferenceMappingList()
    {
        return $this->xmlReferenceMappingList;
    }

}