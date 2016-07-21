<?php

namespace Siesta\Contract;

/**
 * @author Gregor Müller
 */
interface CycleDetector
{

    public function canProceed($tableName, $visitor);

}