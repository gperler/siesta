<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Util\File;

/**
 * @author Gregor Müller
 */
class XMLReader
{
    /**
     * @var \DomDocument
     */
    protected $xmlDocument;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var XMLEntity[]
     */
    protected $xmlEntityList;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->fileName = $file->getAbsoluteFileName();
        $this->xmlDocument = $file->loadAsXML();
        $this->enityList = [];
    }

    /**
     * @return XMLEntity[]
     */
    public function getEntityList() : array
    {

        $domNodeList = $this->xmlDocument->getElementsByTagName(XMLEntity::ELEMENT_ENTITY_NAME);

        foreach ($domNodeList as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $this->handleEntityElement($node);
            }
        }
        return $this->xmlEntityList;
    }

    /**
     * @param \DOMElement $entityElement
     */
    protected function handleEntityElement(\DOMElement $entityElement)
    {
        $entityReader = new XMLEntity();
        $entityReader->fromXML(new XMLAccess($entityElement));
        $this->xmlEntityList[] = $entityReader;
    }
}