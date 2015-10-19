<?php

namespace siestaphp\xmlbuilder;

use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class XMLSPBuilder
 */
class XMLSPBuilder extends XMLBuilder
{

    /**
     * @var StoredProcedureSource
     */
    protected $spSource;

    /**
     * @param StoredProcedureSource $source
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parentElement
     */
    public function __construct(StoredProcedureSource $source, $domDocument, $parentElement)
    {
        parent::__construct($domDocument);

        $this->spSource = $source;

        $this->domElement = $this->createElement($parentElement, XMLStoredProcedure::ELEMENT_STORED_PROCEDURE);

        $this->addSpData();

        $this->addParameterData();
    }

    /**
     * adds standard information about name and result type
     * @return void
     */
    private function addSpData()
    {
        $this->setAttribute(XMLStoredProcedure::ATTRIBUTE_NAME, $this->spSource->getName());
        $this->setAttribute(XMLStoredProcedure::ATTRIBUTE_DATABASE_NAME, $this->spSource->getDatabaseName());
        $this->setAttribute(XMLStoredProcedure::ATTRIBUTE_RESULT_TYPE, $this->spSource->getResultType());

    }

    /**
     * adds information about the used parameters in the stored procedure
     * @return void
     */
    private function addParameterData()
    {
        foreach ($this->spSource->getParameterList() as $parameter) {
            $this->addParameter($parameter);
        }
    }

    /**
     * adds information about a parameter (name, type)
     *
     * @param SPParameterSource $source
     *
     * @return void
     */
    private function addParameter(SPParameterSource $source)
    {
        // create xml container
        $xmlParameter = $this->createElement($this->domElement, XMLStoredProcedure::ELEMENT_PARAMETER);

        // add attributes
        $xmlParameter->setAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_NAME, $source->getName());
        $xmlParameter->setAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_TYPE, $source->getPHPType());
    }

}