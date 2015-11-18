<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\collector\CollectorFilterSource;
use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\naming\XMLCollectorFilter;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class XMLCollectorFilterReader
 * @package siestaphp\xmlreader
 */
class XMLCollectorFilterReader extends XMLAccess implements CollectorFilterSource
{

    /**
     * @var XMLSPParameterReader[]
     */
    protected $spParameterList;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLCollectorFilter::ATTRIBUTE_FILTER_NAME);
    }

    /**
     * @return string
     */
    public function getSPName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->getAttribute(XMLCollectorFilter::ATTRIBUTE_FILTER_FILTER);
    }

    /**
     * @return SPParameterSource
     */
    public function getSPParameterList()
    {
        if ($this->spParameterList === null) {
            $this->readSPParameterList();
        }
        return $this->spParameterList;
    }

    /**
     * @return void
     */
    private function readSPParameterList()
    {
        $this->spParameterList = [];
        $parameterXMLList = $this->getXMLChildElementListByName(XMLStoredProcedure::ELEMENT_PARAMETER);

        foreach ($parameterXMLList as $parameterXML) {
            $parameterReader = new XMLSPParameterReader();
            $parameterReader->setSource($parameterXML);
            $this->spParameterList[] = $parameterReader;
        }

    }

}

