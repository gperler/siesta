<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\ServiceClass\CollectionAccessPlugin;
use Siesta\Model\Collection;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

class CollectorGetterSetter extends BasePlugin
{
    const METHOD_ADD_TO_PREFIX = "addTo";

    /**
     * @param Entity $entity
     *
     * @return array
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useList = [];
        foreach ($entity->getCollectionList() as $collection) {
            $foreignEntity = $collection->getForeignEntity();
            $useList[] = $foreignEntity->getInstantiationClass();
        }
        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $serviceFactory = $foreignEntity->getServiceFactoryClass();
            if ($serviceFactory !== null) {
                $useList[] = $serviceFactory;
            }
        }
        return $useList;
    }

    /**
     * @return array
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

        foreach ($this->entity->getCollectionList() as $collection) {
            $this->generateCollectionGetter($collection);
            $this->generateDeleteAll($collection);
            $this->generateAddToCollection($collection);
        }

    }

    /**
     * @param Collection $collection
     */
    protected function generateCollectionGetter(Collection $collection)
    {
        $methodName = 'get' . $collection->getMethodName();
        $name = $collection->getName();
        $foreignEntity = $collection->getForeignEntity();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter(PHPType::BOOL, 'forceReload', 'false');
        $method->addConnectionNameParameter();
        $method->setReturnType($foreignEntity->getInstantiationClassShortName() . '[]');

        // check if (re)load is needed
        $method->addIfStart('$this->' . $name . ' === null || $forceReload');
        $method->addLine($this->generateLoadCall($collection));
        $method->addIfEnd();

        // return collection
        $method->addLine('return $this->' . $name . ';');
        $method->end();
    }

    /**
     * @param Collection $collection
     *
     * @return string
     */
    protected function generateLoadCall(Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();
        $serviceAccess = $foreignEntity->getServiceAccess();
        $memberName = $collection->getName();

        $methodName = CollectionAccessPlugin::getSelectByReferenceName($collection->getForeignReference());

        $invocationSignature = $this->getServiceClassInvocationSignature();

        return '$this->' . $memberName . ' = ' . $serviceAccess . '->' . $methodName . '(' . $invocationSignature . ', $connectionName);';

    }

    /**
     * @param Collection $collection
     */
    protected function generateDeleteAll(Collection $collection)
    {
        $methodName = "deleteAll" . $collection->getMethodName();
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addConnectionNameParameter();

        // invoke delete call on service class
        $foreignEntity = $collection->getForeignEntity();
        $serviceAccess = $foreignEntity->getServiceAccess();
        $deleteMethodName = CollectionAccessPlugin::getDeleteByReferenceName($collection->getForeignReference());
        $invocationSignature = $this->getServiceClassInvocationSignature();
        $deleteCall = $serviceAccess . '->' . $deleteMethodName . '(' . $invocationSignature . ', $connectionName);';
        $method->addLine($deleteCall);

        // reset collection to null
        $method->addLine('$this->' . $collection->getName() . ' = null;');

        $method->end();
    }

    /**
     * @param Collection $collection
     */
    protected function generateAddToCollection(Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();
        $foreignClass = $foreignEntity->getInstantiationClassShortName();
        $methodName = self::METHOD_ADD_TO_PREFIX . $collection->getMethodName();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter($foreignClass, 'entity');

        // add reference to entity
        $reference = $collection->getForeignReference();
        $method->addLine('$entity->set' . $reference->getMethodName() . '($this);');

        // check if collection is alread array
        $member = '$this->' . $collection->getName();
        $method->addIfStart($member . ' === null');
        $method->addLine($member . ' = [];');
        $method->addIfEnd();

        // add entity to collection
        $method->addLine($member . '[] = $entity;');
        $method->end();
    }

    /**
     * @return string
     */
    protected function getServiceClassInvocationSignature()
    {
        $pkList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $pkList[] = '$this->get' . $pkAttribute->getMethodName() . '(true, $connectionName)';
        }
        return implode(", ", $pkList);
    }

}