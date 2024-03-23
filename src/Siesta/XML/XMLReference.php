<?php
declare(strict_types=1);

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
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $constraintName;

    /**
     * @var string|null
     */
    protected ?string $foreignTable;

    /**
     * @var string|null
     */
    protected ?string $onDelete;

    /**
     * @var string|null
     */
    protected ?string $onUpdate;

    /**
     * @var bool
     */
    protected bool $noConstraint;

    /**
     * @var XMLReferenceMapping[]
     */
    protected array $xmlReferenceMappingList;

    /**
     *
     */
    public function __construct()
    {
        $this->name = null;
        $this->constraintName = null;
        $this->foreignTable = null;
        $this->onDelete = null;
        $this->onUpdate = null;
        $this->noConstraint = false;
        $this->xmlReferenceMappingList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
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
    public function toXML(XMLWrite $parent): void
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

    /**
     * @param ConstraintMetaData $constraintMetaData
     * @return void
     */
    public function fromConstraintMetaData(ConstraintMetaData $constraintMetaData): void
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getForeignTable(): ?string
    {
        return $this->foreignTable;
    }

    /**
     * @param string|null $foreignTable
     */
    public function setForeignTable(?string $foreignTable): void
    {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return string|null
     */
    public function getOnDelete(): ?string
    {
        return $this->onDelete;
    }

    /**
     * @param string|null $onDelete
     */
    public function setOnDelete(?string $onDelete): void
    {
        $this->onDelete = $onDelete;
    }

    /**
     * @return string|null
     */
    public function getOnUpdate(): ?string
    {
        return $this->onUpdate;
    }

    /**
     * @param string|null $onUpdate
     */
    public function setOnUpdate(?string $onUpdate): void
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
    public function setNoConstraint(bool $noConstraint): void
    {
        $this->noConstraint = $noConstraint;
    }

    /**
     * @return string|null
     */
    public function getConstraintName(): ?string
    {
        return $this->constraintName;
    }

    /**
     * @param string|null $constraintName
     */
    public function setConstraintName(?string $constraintName): void
    {
        $this->constraintName = $constraintName;
    }

    /**
     * @return XMLReferenceMapping[]
     */
    public function getXmlReferenceMappingList(): array
    {
        return $this->xmlReferenceMappingList;
    }

}