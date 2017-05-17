<?php

declare(strict_types=1);

namespace Siesta\Util;

use Siesta\Contract\Comparable;
use Siesta\Contract\CycleDetector;

class SaveCycleDetector implements CycleDetector
{
    /**
     * @var array
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
    public function canProceed(string $tableName, Comparable $visitor) : bool
    {
        $entityList = ArrayUtil::getFromArray($this->visitedList, $tableName);

        if ($entityList === null) {
            $this->visitedList [$tableName] = [];
            $this->addVisit($tableName, $visitor);
            return true;
        }

        foreach ($entityList as $entity) {
            if ($visitor->arePrimaryKeyIdentical($entity)) {
                return false;
            }
        }

        $this->addVisit($tableName, $visitor);

        return true;
    }

    /**
     * @param $technicalName
     * @param $visitor
     */
    private function addVisit($technicalName, $visitor)
    {
        $this->visitedList [$technicalName] [] = $visitor;
    }
}