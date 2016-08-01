<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class EntityGenerator extends AbstractGenerator
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
     * @var string
     */
    protected $basePath;

    /**
     * @param Entity $entity
     * @param string $baseDir
     */
    public function generate(Entity $entity, string $baseDir)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->entity = $entity;
        $this->basePath = $baseDir;

        $this->generateEntity();
        $this->saveEntity();
    }

    protected function generateEntity()
    {
        $this->codeGenerator->addNamespace($this->entity->getNamespaceName());

        foreach ($this->getUseClassNameList($this->entity) as $useClass) {
            $this->codeGenerator->addUse($useClass);
        }

        $this->codeGenerator->newLine();

        $this->codeGenerator->addClassStart($this->entity->getClassShortName(), null, $this->getImplementedInterfaceList());

        $this->codeGenerator->newLine();

        foreach ($this->pluginList as $plugin) {
            $plugin->generate($this->entity, $this->codeGenerator);
        }

        $this->codeGenerator->addClassEnd();
    }

    /**
     *
     */
    protected function saveEntity()
    {
        $targetFile = $this->getTargetFile($this->entity, $this->entity->getClassShortName());

        $this->codeGenerator->writeTo($targetFile);
    }

}