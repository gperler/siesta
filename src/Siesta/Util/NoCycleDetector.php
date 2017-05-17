<?php

declare(strict_types=1);

namespace Siesta\Util;

use Siesta\Contract\Comparable;
use Siesta\Contract\CycleDetector;

class NoCycleDetector implements CycleDetector
{
    /**
     * @param string $tableName
     * @param Comparable $visitor
     *
     * @return bool
     */
    public function canProceed(string $tableName, Comparable $visitor): bool
    {
        return true;
    }

}