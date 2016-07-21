<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\ResultSet;

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

    public function close()
    {

    }

    public function getBooleanValue(string $key)
    {
        return null;
    }

    public function getIntegerValue(string $key)
    {
        return null;
    }

    public function getFloatValue(string $key)
    {
        return null;
    }

    public function getStringValue(string $key)
    {
        return null;
    }

    public function getDateTime(string $key)
    {
        return null;
    }

    public function getObject(string $key)
    {
        return null;
    }

    public function getArray(string $key)
    {
        return null;
    }

}