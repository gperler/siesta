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
     * @var string|null
     */
    protected ?string $sqlStatement;

    /**
     * @param string|null $message
     * @param int|null $code
     * @param string|null $sqlStatement
     */
    public function __construct(string $message = null, int $code = null, string $sqlStatement = null)
    {
        parent::__construct($message, $code);
        $this->sqlStatement = $sqlStatement;
    }

    /**
     * @return string|null
     */
    public function getSQL(): ?string
    {
        return $this->sqlStatement;
    }

}
