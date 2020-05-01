<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Nitria\Method;
use Siesta\CodeGenerator\GeneratorHelper;
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
            $useList[] = $foreignEntity->getInstantiationClassName();
            $serviceClass = $foreignEntity->getServiceClass();
            if ($serviceClass !== null) {
                $useList[] = $serviceClass->getConstructFactoryClassName();
            }

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
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);

        foreach ($this->entity->getCollectionList() as $collection) {
            $this->generateCollectionGetter($collection);
            $this->generateDeleteFrom($collection);
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

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $method->addParameter(PHPType::BOOL, 'forceReload', 'false');
        $helper->addConnectionNameParameter();
        $method->setReturnType($foreignEntity->getInstantiationClassName() . '[]');

        // check if (re)load is needed
        $method->addIfStart('$this->' . $name . ' === null || $forceReload');
        $method->addCodeLine($this->generateLoadCall($collection));
        $method->addIfEnd();

        // return collection
        $method->addCodeLine('return $this->' . $name . ';');
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
    protected function generateDeleteFrom(Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();

        $methodName = "deleteFrom" . $collection->getMethodName();
        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $helper->addConnectionNameParameter();

        // invoke delete call on service class
        $serviceAccess = $foreignEntity->getServiceAccess();
        $deleteMethodName = CollectionAccessPlugin::getDeleteByReferenceName($collection->getForeignReference());
        $invocationSignature = $this->getServiceClassInvocationSignature($collection);

        $deleteCall = $serviceAccess . '->' . $deleteMethodName . '(' . $invocationSignature . ', $connectionName);';
        $method->addCodeLine($deleteCall);

        // reset collection to null

        $foreignPKCheckNull = $this->generateParameterNullCheck($collection);
        $method->addIfStart($foreignPKCheckNull);
        $method->addCodeLine('$this->' . $collection->getName() . ' = null;');
        $method->addCodeLine('return;');
        $method->addIfEnd();

        $this->generateSliceCollectionElement($method, $collection);
    }

    protected function generateParameterNullCheck(Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();
        $checkList = [];
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $checkList[] = '$' . $pkAttribute->getPhpName() . ' === null';
        }
        return '($this->' . $collection->getName() . ' === null) OR (' . implode(' AND ', $checkList ) . ')';
    }

    /**
     * @param Method $method
     * @param Collection $collection
     */
    protected function generateSliceCollectionElement(Method $method, Collection $collection)
    {
        $collectionMember = '$this->' . $collection->getName();

        $method->addForeachStart($collectionMember . ' as $index => $entity');

        $comparePKCheck = $this->getEntityComparePrimaryKeyStatement($collection->getForeignEntity());

        $method->addIfStart($comparePKCheck);
        $method->addCodeLine('array_splice(' . $collectionMember . ', $index, 1);');
        $method->addCodeLine('return;');

        $method->addIfEnd();

        $method->addForeachEnd();
    }

    /**
     * @param Entity $foreignEntity
     *
     * @return string
     */
    protected function getEntityComparePrimaryKeyStatement(Entity $foreignEntity) : string
    {
        $compareList = [];
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $attribute) {
            $name = $attribute->getPhpName();
            $methodName = $attribute->getMethodName();

            $compareList[] = '$' . $name . ' === $entity->get' . $methodName . '()';
        }
        return implode(" && ", $compareList);
    }

    /**
     * @param Collection $collection
     */
    protected function generateAddToCollection(Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();
        $foreignClass = $foreignEntity->getInstantiationClassName();
        $methodName = self::METHOD_ADD_TO_PREFIX . $collection->getMethodName();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $method->addParameter($foreignClass, 'entity');

        // add reference to entity
        $reference = $collection->getForeignReference();
        $method->addCodeLine('$entity->set' . $reference->getMethodName() . '($this);');

        // check if collection is already array
        $member = '$this->' . $collection->getName();
        $method->addIfStart($member . ' === null');
        $method->addCodeLine($member . ' = [];');
        $method->addIfEnd();

        // add entity to collection
        $method->addCodeLine($member . '[] = $entity;');
    }

    /**
     * @param Collection|null $collection
     * @return string
     */
    protected function getServiceClassInvocationSignature(Collection $collection = null) : string
    {
        $pkList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $pkList[] = '$this->get' . $pkAttribute->getMethodName() . '(true, $connectionName)';
        }

        if ($collection === null) {
            return implode(", ", $pkList);
        }

        $foreignEntity = $collection->getForeignEntity();

        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $pkList[] = '$' . $pkAttribute->getPhpName();
        }

        return implode(", ", $pkList);
    }

}