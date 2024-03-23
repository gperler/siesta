<?php
declare(strict_types=1);

namespace Siesta\XML;

use DOMElement;
use Siesta\Util\StringUtil;

/**
 * @author Gregor Müller
 */
class XMLAccess
{

    /**
     * @var DOMElement
     */
    protected DOMElement $sourceElement;

    /**
     * XMLAccess constructor.
     * @param DOMElement $sourceElement
     */
    public function __construct(DOMElement $sourceElement)
    {
        $this->sourceElement = $sourceElement;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getAttribute(string $name): ?string
    {
        if ($this->sourceElement->hasAttribute($name)) {
            return StringUtil::trimToNull($this->sourceElement->getAttribute($name));
        }
        return null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function getAttributeAsBool(string $name): bool
    {
        return $this->getAttribute($name) === 'true';
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function getAttributeAsInt(string $name): int
    {
        return (int)$this->getAttribute($name);
    }

    /**
     * @param string $tagName
     *
     * @return XMLAccess[]
     */
    public function getXMLChildElementListByName(string $tagName): array
    {
        $result = [];
        foreach ($this->sourceElement->getElementsByTagName($tagName) as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $result[] = new XMLAccess($child);
            }
        }
        return $result;
    }

    /**
     * @param string $tagName
     *
     * @return array
     */
    public function getDatabaseSpecificAttributeList(string $tagName): array
    {
        $element = $this->getFirstChildByName($tagName);
        if ($element === null) {
            return [];
        }
        return $element->getAttributeList();
    }

    /**
     * @return array
     */
    public function getAttributeList(): array
    {
        $attributeList = [];
        foreach ($this->sourceElement->attributes as $attribute) {
            $attributeList[$attribute->name] = StringUtil::trimToNull($attribute->value);
        }
        return $attributeList;
    }

    /**
     * @param string $tagName
     *
     * @return XMLAccess|null
     */
    public function getFirstChildByName(string $tagName): ?XMLAccess
    {
        $xmlList = $this->getXMLChildElementListByName($tagName);
        if (sizeof($xmlList) === 0) {
            return null;
        }
        return $xmlList[0];
    }

    /**
     * @param string $tagName
     *
     * @return null|string
     */
    public function getFirstChildByNameContent(string $tagName): ?string
    {
        $xmlChild = $this->getFirstChildByName($tagName);
        if ($xmlChild === null) {
            return null;
        }
        return $xmlChild->getTextContent();
    }

    /**
     * @return string|null
     */
    public function getTextContent(): ?string
    {
        return StringUtil::trimToNull($this->sourceElement->textContent);
    }

}