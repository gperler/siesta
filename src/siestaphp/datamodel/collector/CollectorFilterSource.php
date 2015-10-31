<?php

namespace siestaphp\datamodel\collector;

use siestaphp\datamodel\storedprocedure\SPParameterSource;

/**
 * Interface CollectorFilterSource
 * @package siestaphp\datamodel\reference
 */
interface CollectorFilterSource
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSPName();

    /**
     * @return string
     */
    public function getFilter();

    /**
     * @return SPParameterSource[]
     */
    public function getSPParameterList();

}