<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\ServiceClass\GetEntityByIdPlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
class ReferenceGetterSetter extends BasePlugin
{
    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useClassList = [];
        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $useClassList[] = $foreignEntity->getInstantiationClassName();
            $useClassList[] = $foreignEntity->getClassName();
        }

        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $serviceFactory = $foreignEntity->getServiceFactoryClass();
            if ($serviceFactory !== null) {
                $useClassList[] = $serviceFactory;
            }
        }

        return $useClassList;
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return ['Siesta\GeneratorPlugin\Entity\MemberPlugin'];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);

        foreach ($entity->getReferenceList() as $reference) {
            $this->generateReferenceGetter($reference);
            $this->generateReferenceSetter($reference);
        }
    }

    /**
     * @param Reference $reference
     */
    protected function generateReferenceGetter(Reference $reference)
    {
        $foreignEntity = $reference->getForeignEntity();
        $methodName = 'get' . $reference->getMethodName();
        $memberName = '$this->' . $reference->getName();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $method->addParameter(PHPType::BOOL, 'forceReload', 'false');
        $method->setReturnType($foreignEntity->getInstantiationClassName(), true);

        // code to load the entity
        $method->addIfStart($memberName . ' === null || $forceReload');

        $parameter = $this->getFindByPKParameter($reference);
        $reload = $foreignEntity->getServiceAccess() . "->" . GetEntityByIdPlugin::METHOD_ENTITY_BY_PK . "($parameter);";
        $method->addCodeLine($memberName . ' = ' . $reload);

        $method->addIfEnd();

        $method->addCodeLine('return ' . $memberName . ';');
    }

    /**
     * @param Reference $reference
     *
     * @return string
     */
    protected function getFindByPKParameter(Reference $reference)
    {
        $parameterList = [];

        foreach ($reference->getReferenceMappingList() as $mapping) {
            $localAttribute = $mapping->getLocalAttribute();
            $parameterList[] = '$this->' . $localAttribute->getPhpName();
        }

        return implode(", ", $parameterList);
    }

    /**
     * @param Reference $reference
     */
    protected function generateReferenceSetter(Reference $reference)
    {
        $name = $reference->getName();
        $methodName = 'set' . $reference->getMethodName();
        $foreignEntity = $reference->getForeignEntity();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $method->addParameter($foreignEntity->getClassName(), 'entity', 'null');

        $method->addCodeLine('$this->' . $name . ' = $entity;');

        foreach ($reference->getReferenceMappingList() as $referenceMapping) {
            $localAttribute = $referenceMapping->getLocalAttribute();
            $foreignAttribute = $referenceMapping->getForeignAttribute();
            $method->addCodeLine('$this->' . $localAttribute->getPhpName() . ' = ($entity !== null) ? $entity->get' . $foreignAttribute->getMethodName() . '(true) : null;');
        }
    }

}