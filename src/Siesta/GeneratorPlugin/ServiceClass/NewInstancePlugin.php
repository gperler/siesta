<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class NewInstancePlugin extends BasePlugin
{

    const METHOD_NEW_INSTANCE = "newInstance";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity): array
    {
        $useList = [];
        $serviceClass = $entity->getServiceClass();
        if ($serviceClass !== null && $serviceClass->getClassName() !== null) {
            $useList[] = $serviceClass->getClassName();
        }

        if ($serviceClass !== null && $serviceClass->getConstructFactoryClassName() !== null) {
            $useList[] = $serviceClass->getConstructFactoryClassName();
        }

        $constructor = $entity->getConstructor();
        if ($constructor !== null && $constructor->getClassName() !== null) {
            $useList[] = $constructor->getClassName();
        }

        if ($constructor !== null && $constructor->getConstructFactoryClassName() !== null) {
            $useList[] = $constructor->getConstructFactoryClassName();

        }

        return $useList;
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList(): array
    {
        return [];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        $this->generateNewInstance();
    }

    /**
     *
     */
    protected function generateNewInstance(): void
    {

        $instantiationClass = $this->entity->getInstantiationClassName();

        $method = $this->classGenerator->addPublicMethod(self::METHOD_NEW_INSTANCE);
        $method->setReturnType($instantiationClass);

        $method->addCodeLine('return ' . $this->getConstructCall() . ';');
    }

    /**
     * @return string
     */
    protected function getConstructCall(): string
    {
        $constructor = $this->entity->getConstructor();
        if ($constructor !== null && $constructor->getConstructCall() !== null) {
            return $constructor->getConstructCall();
        }
        return 'new ' . $this->entity->getInstantiationClassShortName() . '()';
    }

}