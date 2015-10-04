<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 14:19
 */

namespace siestaphp\xmlreader;


use siestaphp\datamodel\entity\EntitySource;
use siestaphp\naming\XMLEntity;
use siestaphp\util\File;

/**
 * Class XMLReader
 * @package siestaphp\builder
 */
class XMLReader
{


    /**
     * @var \DomDocument
     */
    protected $xmlDocument;

    /**
     * @var EntitySource[]
     */
    protected $entitySourceList;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->fileName = $file->getAbsoluteFileName();
        $this->xmlDocument = $file->loadAsXML();
        $this->enityList = array();
    }


    /**
     * @return EntitySource[]
     */
    public function getEntitySourceList()
    {

        // find entity elements
        $domNodeList = $this->xmlDocument->getElementsByTagName(XMLEntity::ELEMENT_ENTITY_NAME);

        // handle them if they are of type element
        foreach ($domNodeList as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $this->handleEntityElement($node);
            }
        }
        return $this->enityList;
    }

    /**
     * @param \DOMElement $entityElement
     */
    private function handleEntityElement(\DOMElement $entityElement)
    {
        $entityReader = new XMLEntityReader();
        $entityReader->setSource($entityElement);

        $this->enityList[] = $entityReader;
    }





}