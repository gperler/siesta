<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Model\Entity;
use Siesta\Util\File;

class DTOGenerator extends AbstractGenerator
{
    public function generate(Entity $entity, string $baseDir)
    {
        $codeGenerator = new CodeGenerator();
        $codeGenerator->addNamespace($entity->getNamespaceName());

        foreach ($this->getUseClassNameList($entity) as $useClass) {
            $codeGenerator->addUse($useClass);
        }

        $codeGenerator->newLine();

        $codeGenerator->addClassStart($entity->getClassShortName() . "DTO");

        foreach ($this->pluginList as $plugin) {
            $plugin->generate($entity, $codeGenerator);
        }

        $codeGenerator->addClassEnd();

        //$codeGenerator->writeTo(...);
    }

}