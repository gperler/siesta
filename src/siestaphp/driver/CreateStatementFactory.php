<?php


namespace siestaphp\driver;

use siestaphp\datamodel\entity\EntityGeneratorSource;

/**
 * Interface CreateStatementFactory
 * @package siestaphp\driver
 */
interface CreateStatementFactory
{

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