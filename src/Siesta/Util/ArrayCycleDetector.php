<?php
declare(strict_types=1);

namespace Siesta\Util;

use Siesta\Contract\Comparable;
use Siesta\Contract\CycleDetector;

/**
 * @author Gregor Müller
 */
class ArrayCycleDetector implements CycleDetector
{

    /**
     * @var boolean[]
     */
    private $visitedList;

    /**
     *
     */
    public function __construct()
    {
        $this->visitedList = [];
    }

    /**
     * @param $tableName
     * @param $visitor
     *
     * @return bool
     */
    public function canProceed(string $tableName, Comparable $visitor): bool
    {
        $hash = spl_object_hash($visitor);

        $visited = ArrayUtil::getFromArray($this->visitedList, $hash);

        if ($visited === null) {
            $this->visitedList[$hash] = true;
            return true;
        }

        return false;
    }

}