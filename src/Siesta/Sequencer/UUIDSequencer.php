<?php
declare(strict_types = 1);

namespace Siesta\Sequencer;

use Siesta\Model\PHPType;

class UUIDSequencer implements Sequencer
{
    const NAME = "uuid";

    const DB_TYPE = "VARCHAR(36)";

    const PHP_TYPE = PHPType::STRING;

    public function getNextSequence(string $tableName, string $connectionName = null)
    {
        return sprintf('%s-%04x-%04x-%04x%04x%04x', // 32 bits for "time_low"
            dechex(microtime(true) * 10000),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

}