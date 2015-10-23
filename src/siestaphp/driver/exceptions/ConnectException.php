<?php

namespace siestaphp\driver\exceptions;

use siestaphp\driver\ConnectionData;
use siestaphp\exceptions\SiestaException;

/**
 * Class ConnectException
 * @package siestaphp\driver\exceptions
 */
class ConnectException extends SQLException implements SiestaException
{

    /**
     * @var ConnectionData
     */
    protected $connectionData;

    /**
     * @param ConnectionData $connectionData
     * @param string $message
     * @param string $code
     */
    public function  __construct(ConnectionData $connectionData, $message="", $code="") {
        $this->connectionData = $connectionData;
        parent::__construct($message, $code);
    }

    /**
     * @return ConnectionData
     */
    public function getConnectionData()
    {
        return $this->connectionData;
    }

    /**
     * @param ConnectionData $connectionData
     */
    public function setConnectionData($connectionData)
    {
        $this->connectionData = $connectionData;
    }





}

