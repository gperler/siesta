<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 24.06.15
 * Time: 22:51
 */

namespace siestaphp\generator;

use siestaphp\datamodel\Entity;
use siestaphp\datamodel\entity\EntityTransformerSource;

/**
 * Interface Transformer
 * @package siestaphp\generator
 */
interface Transformer {

    /**
     * @param EntityTransformerSource $entity
     * @param string $baseDir
     */
    public function transform(EntityTransformerSource $entity, $baseDir);
}