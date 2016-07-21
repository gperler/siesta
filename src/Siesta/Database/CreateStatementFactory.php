<?php
declare(strict_types = 1);
namespace Siesta\Database;

use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
interface CreateStatementFactory
{

    const SEQUENCER_TABLE_NAME = "SEQUENCER";

    const SEQUENCER_SP_NAME = "SEQUENCER_GETSEQUENCE";

    /**
     * @return string[]
     */
    public function buildSequencer() : array;

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function buildCreateTable(Entity $entity) : array;

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function buildCreateDelimitTable(Entity $entity) : array;

}