<?php

namespace siestaphp\driver;

use siestaphp\datamodel\entity\EntityGeneratorSource;

/**
 * Interface CreateStatementFactory
 * @package siestaphp\driver
 */
interface CreateStatementFactory
{

    const SEQUENCER_TABLE_NAME = "SEQUENCER";

    const SEQUENCER_SP_NAME = "SEQUENCER_GETSEQUENCE";

    /**
     * @return string[]
     */
    public function setupSequencer();

    /**
     * @param EntityGeneratorSource $entity
     *
     * @return string[]
     */
    public function setupTables(EntityGeneratorSource $entity);

    /**
     * @param EntityGeneratorSource $ets
     *
     * @return string[]
     */
    public function setupStoredProcedures(EntityGeneratorSource $ets);
}