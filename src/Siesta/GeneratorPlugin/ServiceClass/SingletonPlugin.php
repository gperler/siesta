<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class SingletonPlugin extends BasePlugin
{

    const METHOD_SINGLETON = "getInstance";

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

        return $useList;
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        $this->generateSingleton();
    }

    /**
     *
     */
    protected function generateSingleton(): void
    {

        $className = $this->entity->getServiceClassInstantiationClassName();
        $classShortName = $this->entity->getServiceClassInstantiationClassNameShort();
        $this->classGenerator->addProtectedStaticProperty("instance", $className, 'null');

        $method = $this->classGenerator->addPublicStaticMethod(self::METHOD_SINGLETON);
        $method->setReturnType($className, false);

        $method->addIfStart('self::$instance === null');
        $method->addCodeLine('self::$instance = new ' . $classShortName . '();');
        $method->addIfEnd();
        $method->addCodeLine('return self::$instance;');
    }

}