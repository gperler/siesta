<?php

namespace siestaphp\driver\mysqli;

/**
 * Class MultiQueryResultSet
 * @package siestaphp\driver\mysqli
 */
class MultiQueryResultSet extends SimpleResultSet
{

    /**
     * @var \mysqli
     */
    private $connection;

    /**
     * @var \mysqli_result
     */
    private $mysqliResult;

    /**
     * @param \mysqli $connection
     */
    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
        $this->mysqliResult = $this->connection->store_result();
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        // nothing in here return false
        if (!$this->mysqliResult) {
            return false;
        }

        // get next row
        $this->next = $this->mysqliResult->fetch_assoc();
        if ($this->next) {
            return true;
        }

        // close result set
        $this->mysqliResult->close();

        // no more results
        return false;
    }

    /**
     * @return array
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     *
     */
    public function close()
    {
        while ($this->connection->more_results()) {
            $this->connection->next_result();
            $this->connection->use_result();
        }

    }
}