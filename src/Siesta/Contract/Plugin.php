<?php

declare(strict_types=1);

namespace Siesta\Contract;

use Nitria\ClassGenerator;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
interface Plugin
{

    /**
     * @param Entity $entity
     *
     * @return array
     */
    public function getUseClassNameList(Entity $entity): array;

    /**
     * @return string[]
     */
    public function getDependantPluginList(): array;

    /**
     * @return array
     */
    public function getInterfaceList(): array;

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void;

}