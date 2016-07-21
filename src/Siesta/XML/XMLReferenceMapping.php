<?php
declare(strict_types = 1);

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
     * @var string
     */
    protected $localAttribute;

    /**
     * @var string
     */
    protected $foreignAttribute;

    /**
     * XMLReferenceMapping constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setLocalAttribute($xmlAccess->getAttribute(self::LOCAL_ATTRIBUTE));
        $this->setForeignAttribute($xmlAccess->getAttribute(self::FOREIGN_ATTRIBUTE));
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_REFERENCE_MAPPING_NAME);
        $xmlWrite->setAttribute(self::LOCAL_ATTRIBUTE, $this->getLocalAttribute());
        $xmlWrite->setAttribute(self::FOREIGN_ATTRIBUTE, $this->getForeignAttribute());
    }

    /**
     * @param ConstraintMappingMetaData $constraintMappingMetaData
     */
    public function fromConstraintMappingMetaData(ConstraintMappingMetaData $constraintMappingMetaData)
    {
        $namingStrategy = NamingStrategyRegistry::getAttributeNamingStrategy();

        $localAttribute = $namingStrategy->transform($constraintMappingMetaData->getLocalColumn());
        $this->setLocalAttribute($localAttribute);

        $foreignAttribute = $namingStrategy->transform($constraintMappingMetaData->getForeignColumn());
        $this->setForeignAttribute($foreignAttribute);
    }

    /**
     * @return string
     */
    public function getLocalAttribute()
    {
        return $this->localAttribute;
    }

    /**
     * @param string $localAttribute
     */
    public function setLocalAttribute($localAttribute)
    {
        $this->localAttribute = $localAttribute;
    }

    /**
     * @return string
     */
    public function getForeignAttribute()
    {
        return $this->foreignAttribute;
    }

    /**
     * @param string $foreignAttribute
     */
    public function setForeignAttribute($foreignAttribute)
    {
        $this->foreignAttribute = $foreignAttribute;
    }

}