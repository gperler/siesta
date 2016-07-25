<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Model\Entity;
use Siesta\Util\File;
use Siesta\Util\StringUtil;

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
        $targetFile = $this->getTargetFile();

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

    /**
     * @return File
     */
    protected function getTargetFile() : File
    {
        $basePath = rtrim($this->basePath, DIRECTORY_SEPARATOR);

        $directoryPath = $basePath . DIRECTORY_SEPARATOR . $this->entity->getTargetPath();

        $directory = new File($directoryPath);
        $directory->createDir();

        $targetFileName = $directoryPath . DIRECTORY_SEPARATOR . $this->entity->getServiceClassShortName() . ".php";
        return new File($targetFileName);
    }

}