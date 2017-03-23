<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin;

use Nitria\ClassGenerator;
use Siesta\Contract\Plugin;
use Siesta\Model\Entity;

abstract class BasePlugin implements Plugin
{

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var ClassGenerator
     */
    protected $classGenerator;

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    protected function setup(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->entity = $entity;
        $this->classGenerator = $classGenerator;
    }

    /**
     * @return string[]
     */
    public function getInterfaceList() : array
    {
        return [];
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return [];
    }

}