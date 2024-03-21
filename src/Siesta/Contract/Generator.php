<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
interface Generator
{

    /**
     * @param Plugin $plugin
     * @return void
     */
    public function addPlugin(Plugin $plugin): void;

    /**
     * @param Entity $entity
     * @param string $baseDir
     * @return void
     */
    public function generate(Entity $entity, string $baseDir): void;

}