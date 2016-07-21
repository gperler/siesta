<?php

declare(strict_types = 1);

namespace Siesta\Util;

use Siesta\Contract\CycleDetector;

/**
 * @author Gregor Müller
 */
class NoCycleDetection implements CycleDetector
{

    /**
     * @param $tableName
     * @param $visitor
     *
     * @return bool
     */
    public function canProceed($tableName, $visitor)
    {
        return true;
    }

}