<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.06.15
 * Time: 18:46
 */

namespace siestaphp\xmlreader;

use siestaphp\util\StringUtil;
use siestaphp\datamodel\DatabaseSpecificSource;

/**
 * Class XMLContainer
 * @package siestaphp\datamodel\xml
 */
class XMLAccess
{

    /**
     * @var \DOMElement
     */
    protected $sourceElement;

    /**
     * @var DatabaseSpecificSource[]
     */
    protected $databaseSpecific;

    /**
     *
     */
    public function __construct()
    {
        $this->databaseSpecific = array();
    }

    /**
     * @param \DOMElement $sourceElement
     */
    public function setSource(\DOMElement $sourceElement)
    {
        $this->sourceElement = $sourceElement;
        $this->extractDatabaseSpecificData($sourceElement->nodeName);

    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getAttribute($name)
    {
        return $this->sourceElement->getAttribute($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function getAttributeAsBool($name)
    {
        return $this->sourceElement->getAttribute($name) === 'true';
    }

    /**
     * returns a list of DOMElements with the given tagName
     *
     * @param $tagName
     *
     * @return \DOMElement[]
     */
    protected function getXMLChildElementListByName($tagName)
    {
        $result = array();
        foreach ($this->sourceElement->getElementsByTagName($tagName) as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * @param string $tagName
     *
     * @return string
     */
    protected function getFirstChildsContentByName($tagName)
    {
        $xmlList = $this->getXMLChildElementListByName($tagName);
        if (is_null($xmlList) || sizeof($xmlList) === 0) {
            return null;
        }
        return $xmlList[0]->textContent;
    }

    /**
     * extract database specific data
     *
     * @param $tagPrefix
     */
    protected function extractDatabaseSpecificData($tagPrefix)
    {

        foreach ($this->sourceElement->childNodes as $childNode) {

            if ($childNode->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            if (StringUtil::startsWith($childNode->tagName, $tagPrefix)) {
                $databaseSpecific = new XMLDatabaseSpecific();
                $databaseSpecific->setSource($childNode);
                $databaseSpecific->setDatabase(str_replace($tagPrefix . "-", "", $childNode->tagName));
                $this->databaseSpecific[] = $databaseSpecific;

            }

        }

    }

    /**
     * @param $database
     *
     * @return DatabaseSpecificSource
     */
    public function getDatabaseSpecific($database)
    {
        foreach ($this->databaseSpecific as $databaseSpecific) {

            if ($databaseSpecific->getDatabase() === $database) {
                return $databaseSpecific;
            }
        }
    }

    /**
     * @return DatabaseSpecificSource[]
     */
    public function getAllDatabaseSpecific()
    {
        return $this->databaseSpecific;
    }

}