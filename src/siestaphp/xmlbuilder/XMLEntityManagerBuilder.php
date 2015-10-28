<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\manager\EntityManagerSource;
use siestaphp\naming\XMLEntityManager;

/**
 * Class XMLEntityManagerBuilder
 * @package siestaphp\xmlbuilder
 */
class XMLEntityManagerBuilder extends XMLBuilder
{

    /**
     * @var EntityManagerSource
     */
    protected $entityManagerSource;

    /**
     * @param EntityManagerSource $source
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(EntityManagerSource $source, $domDocument, $parentElement)
    {
        parent::__construct($domDocument);

        $this->entityManagerSource = $source;

        $this->domElement = $this->createElement($parentElement, XMLEntityManager::ELEMENT_NAME);

        $this->addData();
    }



    protected function addData() {
        $this->setAttribute(XMLEntityManager::ATTRIBUTE_CLASS_NAME, $this->entityManagerSource->getClassName());
        $this->setAttribute(XMLEntityManager::ATTRIBUTE_CLASS_NAMESPACE, $this->entityManagerSource->getClassNamespace());
        $this->setAttribute(XMLEntityManager::ATTRIBUTE_TARGET_PATH, $this->entityManagerSource->getTargetPath());

    }

}