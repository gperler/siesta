<?php


namespace siestaphp\driver;

use siestaphp\datamodel\entity\EntityTransformerSource;

/**
 * Interface TableBuilder
 * @package siestaphp\driver
 */
interface TableBuilder
{

    /**
     * @param EntityTransformerSource $entity
     */
    public function setupTables(EntityTransformerSource $entity);

    /**
     * @param EntityTransformerSource $ets
     */
    public function setupStoredProcedures(EntityTransformerSource $ets);
}