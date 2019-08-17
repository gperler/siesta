<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Nitria\Method;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\ServiceClass\DynamicCollectionAccessPlugin;
use Siesta\Model\DynamicCollection;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

class DynamicCollectionPlugin extends BasePlugin
{


    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);
        $this->generateDynamicCollectionList();
        $this->addUseStatement();
    }

    private function addUseStatement()
    {
        $constructor = $this->entity->getConstructor();
        if ($constructor !== null) {
            $factoryClassName = $constructor->getConstructFactoryClassName();
            if ($factoryClassName !== null) {
                $this->classGenerator->addUsedClassName($factoryClassName);
            }
        }
        $serviceClass = $this->entity->getServiceClass();
        if ($serviceClass !== null) {
            $factoryClassName = $serviceClass->getConstructFactoryClassName();
            if ($factoryClassName !== null) {
                $this->classGenerator->addUsedClassName($factoryClassName);
            }
        }
    }

    protected function generateDynamicCollectionList()
    {
        foreach ($this->entity->getDynamicCollectionList() as $dynamicCollection) {
            $this->generateDynamicCollection($dynamicCollection);
        }
    }

    /**
     * @param DynamicCollection $dynamicCollection
     */
    protected function generateDynamicCollection(DynamicCollection $dynamicCollection)
    {

        $this->addAttribute($dynamicCollection);
        $this->handleMany($dynamicCollection);
    }

    protected function addAttribute(DynamicCollection $dynamicCollection)
    {
        $foreignEntity = $dynamicCollection->getForeignEntity();
        $type = $foreignEntity->getInstantiationClassName() . '[]';
        $this->classGenerator->addProtectedProperty($dynamicCollection->getName(), $type);
    }

    protected function handleMany(DynamicCollection $dynamicCollection)
    {
        $this->generateAddToCollection($dynamicCollection);
        $this->generateGetCollection($dynamicCollection);
        $this->generateDeleteFromCollection($dynamicCollection);
    }

    protected function generateAddToCollection(DynamicCollection $dynamicCollection)
    {
        $foreignEntity = $dynamicCollection->getForeignEntity();
        $foreignClass = $foreignEntity->getInstantiationClassName();

        $method = $this->classGenerator->addMethod("addTo" . $dynamicCollection->getMethodName());
        $method->addParameter($foreignClass, 'entity');

        // check if collection is already array
        $member = '$this->' . $dynamicCollection->getName();
        $method->addIfStart($member . ' === null');
        $method->addCodeLine($member . ' = [];');
        $method->addIfEnd();

        $method->addCodeLine('$entity->set_foreignTable(self::TABLE_NAME);');
        $method->addCodeLine('$entity->set_foreignName("' . $dynamicCollection->getName() . '");');

        $pkAccess = $this->generatePKAccess();
        $method->addCodeLine('$entity->set_foreignId(' . $pkAccess . ');');

        // add entity to collection
        $method->addCodeLine($member . '[] = $entity;');
    }

    protected function generateGetCollection(DynamicCollection $dynamicCollection)
    {
        $methodName = 'get' . $dynamicCollection->getMethodName();
        $name = $dynamicCollection->getName();
        $foreignEntity = $dynamicCollection->getForeignEntity();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $method->addParameter(PHPType::BOOL, 'forceReload', 'false');
        $helper->addConnectionNameParameter();
        $method->setReturnType($foreignEntity->getInstantiationClassName() . '[]');

        // check if (re)load is needed
        $method->addIfStart('$this->' . $name . ' === null || $forceReload');
        $method->addCodeLine($this->generateLoadCall($dynamicCollection));
        $method->addIfEnd();

        // return collection
        $method->addCodeLine('return $this->' . $name . ';');

    }

    /**
     * @param DynamicCollection $collection
     *
     * @return string
     */
    protected function generateLoadCall(DynamicCollection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();
        $serviceAccess = $foreignEntity->getServiceAccess();
        $memberName = $collection->getName();

        $methodName = DynamicCollectionAccessPlugin::NAME_ACCESS_MANY;

        $invocationSignature = $this->getInvocationSignature($collection);

        return '$this->' . $memberName . ' = ' . $serviceAccess . '->' . $methodName . '(' . $invocationSignature . ', $connectionName);';

    }

    protected function generateDeleteFromCollection(DynamicCollection $dynamicCollection)
    {
        $foreignEntity = $dynamicCollection->getForeignEntity();

        $methodName = "deleteFrom" . $dynamicCollection->getMethodName();
        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $helper->addConnectionNameParameter();

        // invoke delete call on service class
        $serviceAccess = $foreignEntity->getServiceAccess();
        $deleteMethodName = DynamicCollectionAccessPlugin::NAME_DELETE;
        $invocationSignature = $this->getInvocationSignature($dynamicCollection);

        $deleteCall = $serviceAccess . '->' . $deleteMethodName . '(' . $invocationSignature . ', $connectionName);';
        $method->addCodeLine($deleteCall);

        // reset collection to null

        $foreignPKCheckNull = $this->generateParameterNullCheck($dynamicCollection);
        $method->addIfStart($foreignPKCheckNull);
        $method->addCodeLine('$this->' . $dynamicCollection->getName() . ' = null;');
        $method->addCodeLine('return;');
        $method->addIfEnd();

        $this->generateSliceCollectionElement($method, $dynamicCollection);
    }

    protected function generateParameterNullCheck(DynamicCollection $dynamicCollection)
    {
        $foreignEntity = $dynamicCollection->getForeignEntity();
        $checkList = [];
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $checkList[] = '$' . $pkAttribute->getPhpName() . ' === null';
        }
        return '($this->' . $dynamicCollection->getName() . ' === null) OR (' . implode($checkList, ' AND ') . ')';
    }

    /**
     * @param Method $method
     * @param DynamicCollection $dynamicCollection
     */
    protected function generateSliceCollectionElement(Method $method, DynamicCollection $dynamicCollection)
    {
        $collectionMember = '$this->' . $dynamicCollection->getName();

        $method->addForeachStart($collectionMember . ' as $index => $entity');

        $comparePKCheck = $this->getEntityComparePrimaryKeyStatement($dynamicCollection->getForeignEntity());

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
    protected function getEntityComparePrimaryKeyStatement(Entity $foreignEntity): string
    {
        $compareList = [];
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $attribute) {
            $name = $attribute->getPhpName();
            $methodName = $attribute->getMethodName();

            $compareList[] = '$' . $name . ' === $entity->get' . $methodName . '()';
        }
        return implode(" && ", $compareList);
    }

    protected function getInvocationSignature(DynamicCollection $dynamicCollection)
    {
        return 'self::TABLE_NAME, "' . $dynamicCollection->getName() . '", ' . $this->generatePKAccess();
    }

    protected function generatePKAccess()
    {
        // TODO: Add to validation dynamic collection only for UUID and single PK Entities
        $attributeList = $this->entity->getPrimaryKeyAttributeList();
        $pkAttribute = $attributeList[0];
        return '$this->get' . $pkAttribute->getMethodName() . '(true)';
    }

}