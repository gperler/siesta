<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\ServiceClass;

/**
 * @author Gregor Müller
 */
class SingletonPlugin extends BasePlugin
{

    const METHOD_SINGLETON = "getInstance";

    /**
     * @var ServiceClass
     */
    protected $serviceClass;

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

        $this->generateSingleton();
    }

    /**
     *
     */
    protected function generateSingleton()
    {
        $instantiationClass = $this->getInstantiationClass();

        $this->codeGenerator->addStaticProtectedMember("instance", $instantiationClass);

        $method = $this->codeGenerator->newPublicStaticMethod(self::METHOD_SINGLETON);
        $method->setReturnType($instantiationClass);

        $method->addIfStart('self::$instance === null');
        $method->addLine('self::$instance = new ' . $instantiationClass . '();');
        $method->addIfEnd();
        $method->addLine('return self::$instance;');

        $method->end();

    }

    /**
     * @return null|string
     */
    protected function getInstantiationClass()
    {
        $serviceClass = $this->entity->getServiceClass();
        if ($serviceClass !== null && $serviceClass->getClassShortName() !== null) {
            return $serviceClass->getClassShortName();
        }
        return $this->entity->getServiceClassShortName();

    }

}