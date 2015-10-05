<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\driver\Driver;

/**
 * Interface StoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
interface StoredProcedure {

    /**
     * @param Driver $driver
     */
    public function createProcedure(Driver $driver);


    /**
     * @param Driver $driver
     */
    public function dropProcedure(Driver $driver);
}