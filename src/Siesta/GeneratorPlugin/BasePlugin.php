<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin;

use Nitria\ClassGenerator;
use Siesta\Contract\Plugin;
use Siesta\Model\Entity;

abstract class BasePlugin implements Plugin
{

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var ClassGenerator
     */
    protected ClassGenerator $classGenerator;

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    protected function setup(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->entity = $entity;
        $this->classGenerator = $classGenerator;
    }

    /**
     * @return string[]
     */
    public function getInterfaceList(): array
    {
        return [];
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList(): array
    {
        return [];
    }

}