<?php

namespace siestaphp\datamodel\storedprocedure;

/**
 * Interface StoredProcedureSource
 * @package siestaphp\datamodel
 */
interface StoredProcedureSource
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return SPParameterSource[]
     */
    public function getParameterList();

    /**
     * @return bool
     */
    public function modifies();

    /**
     * @param string $databaseName
     *
     * @return string
     */
    public function getSql($databaseName = null);

    /**
     * @return string
     */
    public function getDatabaseName();

    /**
     * @return string
     */
    public function getResultType();
}