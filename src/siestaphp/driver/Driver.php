<?php

namespace siestaphp\driver;

use siestaphp\driver\exceptions\ConnectException;

/**
 * Interface Driver
 * @package siestaphp\driver
 */
interface Driver
{

    /**
     * @param ConnectionData $connectionData
     *
     * @return Connection
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData);

}