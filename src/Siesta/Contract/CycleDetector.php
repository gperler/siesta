<?php

namespace Siesta\Contract;

/**
 * @author Gregor Müller
 */
interface CycleDetector
{

    /**
     * @param string $tableName
     * @param Comparable $visitor
     *
     * @return bool
     */
    public function canProceed(string $tableName, Comparable $visitor): bool;

}