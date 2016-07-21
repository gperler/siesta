<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
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
    public function getUseClassNameList(Entity $entity) : array
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
    public function getDependantPluginList() : array
    {
        return [];
    }

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);

        $this->generateNewInstance();
    }

    /**
     *
     */
    protected function generateNewInstance()
    {

        $instantiationClass = $this->entity->getInstantiationClassShortName();

        $method = $this->codeGenerator->newPublicMethod(self::METHOD_NEW_INSTANCE);
        $method->setReturnType($instantiationClass);

        $method->addLine('return ' . $this->getConstructCall() . ';');

        $method->end();

    }

    /**
     * @return string
     */
    protected function getConstructCall()
    {
        $constructor = $this->entity->getConstructor();
        if ($constructor !== null && $constructor->getConstructCall() !== null) {
            return $constructor->getConstructCall();
        }
        return 'new ' . $this->entity->getInstantiationClassShortName() . '()';
    }

}