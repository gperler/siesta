<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\driver\Connection;

/**
 * Interface StoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
interface StoredProcedure
{

    /**
     * @param Connection $connection
     */
    public function createProcedure(Connection $connection);

    /**
     * @param Connection $connection
     */
    public function dropProcedure(Connection $connection);
}