<?php

declare(strict_types=1);

namespace Siesta\Generator;

use Nitria\ClassGenerator;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class EntityGenerator extends AbstractGenerator
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
     * @var string
     */
    protected string $basePath;

    /**
     * @param Entity $entity
     * @param string $baseDir
     */
    public function generate(Entity $entity, string $baseDir): void
    {
        $this->classGenerator = new ClassGenerator($entity->getClassName());
        $this->entity = $entity;
        $this->basePath = $baseDir;

        $this->generateEntity();
        $this->saveEntity();
    }

    /**
     *
     */
    protected function generateEntity(): void
    {

        foreach ($this->getUseClassNameList($this->entity) as $useClass) {
            $this->classGenerator->addUsedClassName($useClass);
        }

        foreach ($this->getImplementedInterfaceList() as $interfaceName) {
            $this->classGenerator->addImplements($interfaceName);
        }

        foreach ($this->pluginList as $plugin) {
            $plugin->generate($this->entity, $this->classGenerator);
        }
    }

    /**
     *
     */
    protected function saveEntity(): void
    {
        $targetFile = $this->getTargetFile($this->entity, $this->entity->getClassShortName());
        $this->classGenerator->writeToFile($targetFile);
    }

}