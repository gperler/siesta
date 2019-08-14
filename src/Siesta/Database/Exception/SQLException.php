<?php
declare(strict_types = 1);
namespace Siesta\Database\Exception;

use RuntimeException;
use Siesta\Exception\SiestaException;

/**
 * @author Gregor Müller
 */
class SQLException extends RuntimeException implements SiestaException
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
    public function __construct(string $message = null, int $code = null, string $sqlStatement = null)
    {
        parent::__construct($message, $code);
        $this->sqlStatement = $sqlStatement;
    }

    /**
     * @return string
     */
    public function getSQL()
    {
        return $this->sqlStatement;
    }

}
