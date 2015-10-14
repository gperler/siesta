<?php


namespace siestaphp\driver;

use siestaphp\datamodel\entity\EntityGeneratorSource;

/**
 * Interface TableBuilder
 * @package siestaphp\driver
 */
interface TableBuilder
{

    /**
     * @param EntityGeneratorSource $entity
     */
    public function setupTables(EntityGeneratorSource $entity);

    /**
     * @param EntityGeneratorSource $ets
     */
    public function setupStoredProcedures(EntityGeneratorSource $ets);
}