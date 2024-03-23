<?php
declare(strict_types=1);

namespace Siesta\Sequencer;

use Siesta\Database\ConnectionFactory;

class AutoincrementSequencer implements Sequencer
{
    const NAME = "autoincrement";

    const DB_TYPE = "INT";

    const PHP_TYPE = "int";

    /**
     * @param string $tableName
     * @param string|null $connectionName
     *
     * @return int
     */
    public function getNextSequence(string $tableName, string $connectionName = null): mixed
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        return $connection->getSequence($tableName);
    }

}