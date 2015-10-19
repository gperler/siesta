<?php

namespace siestaphp\xmlbuilder;

/**
 * Class XMLBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLBuilder
{

    /**
     * @var \DOMDocument
     */
    protected $domDocument;

    /**
     * @var \DOMElement
     */
    protected $domElement;

    /**
     * @param \DOMDocument $domDocument
     */
    public function __construct($domDocument)
    {
        $this->domDocument = ($domDocument) ? $domDocument : new \DOMDocument();
    }

    /**
     * creates an xml element and appends it to the parent
     *
     * @param \DOMNode $parent
     * @param string $name
     *
     * @return \DOMElement
     */
    protected function createElement(\DOMNode $parent, $name)
    {
        $element = $this->domDocument->createElement($name);
        $parent->appendChild($element);
        return $element;
    }

    /**
     * sets an xml attribute on the current dom element
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    protected function setAttribute($name, $value)
    {
        $this->domElement->setAttribute($name, $value);
    }

    /**
     * sets an xml attribute as true|false on the current dom element
     *
     * @param string $name
     * @param string $value
     *
     * @return void^
     */
    protected function setAttributeAsBool($name, $value)
    {
        $value = ($value) ? "true" : "false";
        $this->domElement->setAttribute($name, $value);
    }

    /**
     * returns the current DOMDocument
     * @return \DOMDocument
     */
    public function getDOMDocument()
    {
        return $this->domDocument;
    }

}