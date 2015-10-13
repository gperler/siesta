<?php


namespace siestaphp\driver\exceptions;

use siestaphp\exceptions\SiestaException;

/**
 * Class SQLException
 * @package siestaphp\driver\exceptions
 */
class SQLException extends \Exception implements SiestaException
{

    /**
     * @var string
     */
    protected $sqlStatement;

    /**
     * @param string $message
     * @param int $code
     * @param string $sqlStatement
     */
    public function __construct($message=null, $code = null, $sqlStatement=null) {
        parent::__construct($message, $code);
        $this->sqlStatement = $sqlStatement;
    }


}
