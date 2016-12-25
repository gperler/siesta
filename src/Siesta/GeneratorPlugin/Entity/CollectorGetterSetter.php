<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\CodeGenerator\MethodGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\ServiceClass\CollectionAccessPlugin;
use Siesta\Model\Collection;
use Siesta\Model\CollectionMany;
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
    protected function generateDeleteFrom(Collection $collection)
    {
        $foreignEntity = $collection->getForeignEntity();

        $methodName = "deleteFrom" . $collection->getMethodName();
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $method->addConnectionNameParameter();

        // invoke delete call on service class
        $serviceAccess = $foreignEntity->getServiceAccess();
        $deleteMethodName = CollectionAccessPlugin::getDeleteByReferenceName($collection->getForeignReference());
        $invocationSignature = $this->getServiceClassInvocationSignature($collection);

        $deleteCall = $serviceAccess . '->' . $deleteMethodName . '(' . $invocationSignature . ', $connectionName);';
        $method->addLine($deleteCall);

        // reset collection to null

        $foreignPKCheckNull = $this->generateParameterNullCheck($collection);
        $method->addIfStart($foreignPKCheckNull);
        $method->addLine('$this->' . $collection->getName() . ' = null;');
        $method->addLine('return;');
        $method->addIfEnd();

        $this->generateSliceCollectionElement($method, $collection);

        $method->end();
    }


    protected function generateParameterNullCheck(Collection $collection) {
        $foreignEntity = $collection->getForeignEntity();
        $checkList = [];
        foreach($foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $checkList[] = '$' . $pkAttribute->getPhpName() . ' === null';
        }
        return '($this->' . $collection->getName() . ' === null) OR (' .implode($checkList, ' AND ') . ')';
    }


    protected function generateSliceCollectionElement(MethodGenerator $method, Collection $collection) {
        $collectionMember = '$this->' . $collection->getName();


        $method->addForeachStart($collectionMember . ' as $index => $entity');

        $comparePKCheck = $this->getEntityComparePrimaryKeyStatement($collection->getForeignEntity());

        $method->addIfStart($comparePKCheck);
        $method->addLine('array_splice(' . $collectionMember . ', $index, 1);');
        $method->addLine('return;');

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
    protected function getServiceClassInvocationSignature(Collection $collection = null)
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