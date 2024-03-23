<?php

declare(strict_types=1);

namespace Siesta\XML;

use DOMDocument;
use DOMElement;
use Siesta\Util\File;

class XMLWrite
{

    /**
     * @var DOMDocument
     */
    protected DOMDocument $document;

    /**
     * @var DOMElement|null
     */
    protected ?DOMElement $element;

    /**
     * XMLWrite constructor.
     *
     * @param DOMDocument $document
     * @param DOMElement|null $element
     * @param string|null $tagName
     */
    public function __construct(DOMDocument $document, ?DOMElement $element, string $tagName = null)
    {
        $this->document = $document;
        if ($element !== null) {
            $this->element = $element;
        } else {
            $this->element = $document->createElement($tagName);
            $document->appendChild($this->element);
        }
    }

    /**
     * @param string $key
     * @param string|null $value
     */
    public function setAttribute(string $key, string $value = null): void
    {
        if ($value !== null) {
            $this->element->setAttribute($key, $value);
        }

    }

    /**
     * @param string $key
     * @param int|null $value
     */
    public function setIntAttribute(string $key, int $value = null): void
    {
        $this->element->setAttribute($key, (string)$value);
    }

    /**
     * @param string $key
     * @param bool $value
     */
    public function setBoolAttribute(string $key, bool $value = null): void
    {
        $value = ($value === true) ? 'true' : 'false';
        $this->setAttribute($key, $value);
    }

    /**
     * @param string $key
     * @param bool $value
     */
    public function setBoolAttributeIfTrue(string $key, bool $value = null): void
    {
        if ($value === null || $value === false) {
            return;
        }
        $this->setAttribute($key, 'true');
    }

    /**
     * @param string $name
     *
     * @return XMLWrite
     */
    public function appendChild(string $name): XMLWrite
    {
        $element = $this->document->createElement($name);
        $this->element->appendChild($element);

        return new XMLWrite($this->document, $element);
    }

    /**
     * @param File $file
     */
    public function saveToFile(File $file): void
    {
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
        $xmlString = $this->document->saveXML();
        $file->putContents($xmlString);
    }
}