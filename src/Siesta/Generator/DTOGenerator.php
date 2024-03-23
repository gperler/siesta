<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Nitria\ClassGenerator;
use Siesta\Model\Entity;

class DTOGenerator extends AbstractGenerator
{
    /**
     * @param Entity $entity
     * @param string $baseDir
     * @return void
     */
    public function generate(Entity $entity, string $baseDir): void
    {
        $classGenerator = new ClassGenerator($entity->getClassName() . "DTO");

        foreach ($this->getUseClassNameList($entity) as $useClass) {
            $classGenerator->addUsedClassName($useClass);
        }

        foreach ($this->pluginList as $plugin) {
            $plugin->generate($entity, $classGenerator);
        }
    }

}