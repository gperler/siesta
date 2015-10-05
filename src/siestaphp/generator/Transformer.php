<?php

namespace siestaphp\generator;

use siestaphp\datamodel\Entity;
use siestaphp\datamodel\entity\EntityTransformerSource;

/**
 * Interface Transformer
 * @package siestaphp\generator
 */
interface Transformer
{

    /**
     * @param EntityTransformerSource $entity
     * @param string $baseDir
     */
    public function transform(EntityTransformerSource $entity, $baseDir);
}