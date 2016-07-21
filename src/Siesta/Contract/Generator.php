<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
interface Generator
{

    public function addPlugin(Plugin $plugin);

    public function generate(Entity $entity, string $baseDir);

}