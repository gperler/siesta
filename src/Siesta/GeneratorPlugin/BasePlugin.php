<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Contract\Plugin;
use Siesta\Model\Entity;

abstract class BasePlugin implements Plugin
{

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var CodeGenerator
     */
    protected $codeGenerator;

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    protected function setup(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->entity = $entity;
        $this->codeGenerator = $codeGenerator;
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