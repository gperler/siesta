<?php

declare(strict_types=1);

namespace Siesta\Util;

use Siesta\Contract\CycleDetector;

class NoCycleDetector implements CycleDetector
{
    public function canProceed($tableName, $visitor)
    {
        return true;
    }

}