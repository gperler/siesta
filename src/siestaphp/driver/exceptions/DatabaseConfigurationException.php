<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 12.10.15
 * Time: 16:57
 */

namespace siestaphp\driver\exceptions;

use siestaphp\driver\ConnectionData;
use siestaphp\exceptions\SiestaException;

/**
 * Class DatabaseConfigurationException
 * @package siestaphp\driver\exceptions
 */
class DatabaseConfigurationException extends \Exception implements SiestaException
{
    /**
     * @param ConnectionData $connectionData
     * @param string $message
     */
    public function __construct(ConnectionData $connectionData, $message=null)
    {
        parent::__construct($message);
    }

}