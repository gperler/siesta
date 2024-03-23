<?php
declare(strict_types=1);

namespace Siesta\Sequencer;

use Siesta\Model\PHPType;

class UUIDSequencer implements Sequencer
{
    const NAME = "uuid";

    const DB_TYPE = "VARCHAR(36)";

    const PHP_TYPE = PHPType::STRING;

    /**
     * @param string $tableName
     * @param string|null $connectionName
     * @return mixed
     */
    public function getNextSequence(string $tableName, string $connectionName = null): mixed
    {
        $microTime = dechex(intval(microtime(true) * 10000));
        if (strlen($microTime) === 11) {
            $microTime = "0" . $microTime;
        }

        return sprintf("%s-%s-%04x-%04x-%04x%04x%04x",
            substr($microTime, 0, 8),
            substr($microTime, 8, 4),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

}