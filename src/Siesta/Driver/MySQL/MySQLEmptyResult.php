<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\ResultSet;
use Siesta\Util\SiestaDateTime;

/**
 * @author Gregor Müller
 */
class MySQLEmptyResult implements ResultSet
{
    public function hasNext() : bool
    {
        return false;
    }

    public function getNext() : array
    {
        return [];
    }

    public function close(): void
    {

    }

    public function getBooleanValue(string $key): ?bool
    {
        return null;
    }

    public function getIntegerValue(string $key): ?int
    {
        return null;
    }

    public function getFloatValue(string $key): ?float
    {
        return null;
    }

    public function getStringValue(string $key): ?string
    {
        return null;
    }

    public function getDateTime(string $key): ?SiestaDateTime
    {
        return null;
    }

    public function getObject(string $key): mixed
    {
        return null;
    }

    public function getArray(string $key): ?array
    {
        return null;
    }

}