<?php
declare(strict_types=1);

namespace Siesta\Sequencer;

interface Sequencer
{
    public function getNextSequence(string $tableName, string $connectionName = null): mixed;
}