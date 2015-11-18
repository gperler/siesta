<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class XMLStoredProcedureReader
 * @package siestaphp\xmlreader
 */
class XMLStoredProcedureReader extends XMLAccess implements StoredProcedureSource
{

    /**
     * @var XMLSPParameterReader[]
     */
    protected $spParameterReaderList;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->spParameterReaderList = null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_NAME);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_FILE);
    }

    /**
     * @return bool
     */
    public function modifies()
    {
        return $this->getAttributeAsBool(XMLStoredProcedure::ATTRIBUTE_MODIFIES);
    }

    /**
     * @return SPParameterSource[]
     */
    public function getParameterList()
    {

        if ($this->spParameterReaderList === null) {
            $this->readParameterList();
        }

        return $this->spParameterReaderList;

    }

    /**
     * @return void
     */
    private function readParameterList()
    {
        $this->spParameterReaderList = [];
        $xmlParameterList = $this->getXMLChildElementListByName(XMLStoredProcedure::ELEMENT_PARAMETER);
        foreach ($xmlParameterList as $xmlParameter) {
            $parameterReader = new XMLSPParameterReader();
            $parameterReader->setSource($xmlParameter);
            $this->spParameterReaderList[] = $parameterReader;

        }

    }

    /**
     * @param string $databaseName
     *
     * @return string
     */
    public function getSql($databaseName = null)
    {
        $sql = $this->getFirstChildsContentByName(XMLStoredProcedure::ELEMENT_SQL . "-" . $databaseName);

        if (!is_null($sql)) {
            return trim($sql);
        }

        return trim($this->getFirstChildsContentByName(XMLStoredProcedure::ELEMENT_SQL));
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getResultType()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_RESULT_TYPE);
    }

}
