<?php
declare(strict_types = 1);
namespace Siesta\Database;

use Siesta\Database\Exception\ConnectException;

/**
 * @author Gregor Müller
 */
interface Driver
{

    /**
     * @param ConnectionData $connectionData
     *
     * @return Connection
     * @throws ConnectException
     */
    public function connect(ConnectionData $connectionData) : Connection;

}