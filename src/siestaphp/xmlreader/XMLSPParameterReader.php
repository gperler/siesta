<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\naming\XMLStoredProcedure;

/**
 * Class XMLSPParameterReader
 * @package siestaphp\xmlreader
 */
class XMLSPParameterReader extends XMLAccess implements SPParameterSource
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_NAME);
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_TYPE);
    }

    /**
     * @return string
     */
    public function getStoredProcedureName()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_SP_NAME);
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->getAttribute(XMLStoredProcedure::ATTRIBUTE_PARAMETER_DATABASE_TYPE);
    }

}