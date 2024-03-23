<?php
declare(strict_types=1);

namespace Siesta\XML;

use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\NamingStrategy\NamingStrategyRegistry;

/**
 * @author Gregor Müller
 */
class XMLReferenceMapping
{

    const ELEMENT_REFERENCE_MAPPING_NAME = "referenceMapping";

    const LOCAL_ATTRIBUTE = "localAttribute";

    const FOREIGN_ATTRIBUTE = "foreignAttribute";

    /**
     * @var string|null
     */
    protected ?string $localAttribute;

    /**
     * @var string|null
     */
    protected ?string $foreignAttribute;

    /**
     * XMLReferenceMapping constructor.
     */
    public function __construct()
    {
        $this->localAttribute = null;
        $this->foreignAttribute = null;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setLocalAttribute($xmlAccess->getAttribute(self::LOCAL_ATTRIBUTE));
        $this->setForeignAttribute($xmlAccess->getAttribute(self::FOREIGN_ATTRIBUTE));
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent): void
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_REFERENCE_MAPPING_NAME);
        $xmlWrite->setAttribute(self::LOCAL_ATTRIBUTE, $this->getLocalAttribute());
        $xmlWrite->setAttribute(self::FOREIGN_ATTRIBUTE, $this->getForeignAttribute());
    }

    /**
     * @param ConstraintMappingMetaData $constraintMappingMetaData
     */
    public function fromConstraintMappingMetaData(ConstraintMappingMetaData $constraintMappingMetaData): void
    {
        $namingStrategy = NamingStrategyRegistry::getAttributeNamingStrategy();

        $localAttribute = $namingStrategy->transform($constraintMappingMetaData->getLocalColumn());
        $this->setLocalAttribute($localAttribute);

        $foreignAttribute = $namingStrategy->transform($constraintMappingMetaData->getForeignColumn());
        $this->setForeignAttribute($foreignAttribute);
    }

    /**
     * @return string|null
     */
    public function getLocalAttribute(): ?string
    {
        return $this->localAttribute;
    }

    /**
     * @param string|null $localAttribute
     */
    public function setLocalAttribute(?string $localAttribute): void
    {
        $this->localAttribute = $localAttribute;
    }

    /**
     * @return string|null
     */
    public function getForeignAttribute(): ?string
    {
        return $this->foreignAttribute;
    }

    /**
     * @param string|null $foreignAttribute
     */
    public function setForeignAttribute(?string $foreignAttribute): void
    {
        $this->foreignAttribute = $foreignAttribute;
    }

}