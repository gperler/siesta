<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\driver\Connection;

/**
 * Interface MySQLStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
interface MySQLStoredProcedure
{

    /**
     * @return string
     */
    public function buildCreateProcedureStatement();

    /**
     * @return string
     */
    public function buildProcedureDropStatement();
}