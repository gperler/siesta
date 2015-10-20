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
    public function buildSequencer();

    /**
     * @param EntityGeneratorSource $entity
     *
     * @return string[]
     */
    public function buildCreateTable(EntityGeneratorSource $entity);


    /**
     * @param EntityGeneratorSource $entity
     *
     * @return string[]
     */
    public function buildCreateDelimitTable(EntityGeneratorSource $entity);

    /**
     * @param EntityGeneratorSource $ets
     *
     * @return string[]
     */
    public function buildCreateStoredProcedures(EntityGeneratorSource $ets);
}