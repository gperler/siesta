<?php

declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Util\File;

class XMLWrite
{

    /**
     * @var \DOMDocument
     */
    protected $document;

    /**
     * @var \DOMElement
     */
    protected $element;

    /**
     * XMLWrite constructor.
     *
     * @param \DOMDocument $document
     * @param \DOMElement|null $element
     * @param string|null $tagName
     */
    public function __construct(\DOMDocument $document, \DOMElement $element = null, string $tagName = null)
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
     * @param string $value
     */
    public function setAttribute(string $key, string $value = null)
    {
        if ($value !== null) {
            $this->element->setAttribute($key, $value);
        }

    }

    /**
     * @param string $key
     * @param int $value
     */
    public function setIntAttribute(string $key, int $value = null)
    {
        $this->element->setAttribute($key, (string)$value);
    }

    /**
     * @param string $key
     * @param bool $value
     */
    public function setBoolAttribute(string $key, bool $value = null)
    {
        $value = ($value === true) ? 'true' : 'false';
        $this->setAttribute($key, $value);
    }

    /**
     * @param string $key
     * @param bool $value
     */
    public function setBoolAttributeIfTrue(string $key, bool $value = null)
    {
        if ($value === null || $value === false) {
            return;
        }
        $this->setAttribute($key, 'true');
    }

    /**
     * @param string $name
     * @param string|null $value
     *
     * @return XMLWrite
     */
    public function appendChild(string $name, string $value = null)
    {
        $element = $this->document->createElement($name);
        $this->element->appendChild($element);

        return new XMLWrite($this->document, $element);
    }

    /**
     * @param File $file
     */
    public function saveToFile(File $file)
    {
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
        $xmlString = $this->document->saveXML();
        $file->putContents($xmlString);
    }
}