<?php

namespace siestaphp\generator;

use siestaphp\datamodel\entity\EntityGeneratorSource;

/**
 * Interface Transformer
 * @package siestaphp\generator
 */
interface Transformer
{

    /**
     * @param EntityGeneratorSource $entity
     * @param string $baseDir
     */
    public function transform(EntityGeneratorSource $entity, $baseDir);
}