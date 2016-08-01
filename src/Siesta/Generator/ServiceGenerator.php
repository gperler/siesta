<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ServiceGenerator extends AbstractGenerator
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

    public function generate(Entity $entity, string $baseDir)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->entity = $entity;
        $this->basePath = $baseDir;

        $this->generateServiceEntity();
        $this->saveServiceEntity();
    }

    /**
     *
     */
    protected function saveServiceEntity()
    {
        $targetFile = $this->getTargetFile($this->entity, $this->entity->getServiceClassShortName());

        $this->codeGenerator->writeTo($targetFile);
    }

    /**
     *
     */
    protected function generateServiceEntity()
    {
        $this->codeGenerator->addNamespace($this->entity->getNamespaceName());

        foreach ($this->getUseClassNameList($this->entity) as $useClass) {
            $this->codeGenerator->addUse($useClass);
        }

        $this->codeGenerator->newLine();

        $this->codeGenerator->addClassStart($this->entity->getServiceClassShortName(), null, $this->getImplementedInterfaceList());

        $this->codeGenerator->newLine();

        foreach ($this->pluginList as $plugin) {
            $plugin->generate($this->entity, $this->codeGenerator);
        }

        $this->codeGenerator->addClassEnd();
    }

}