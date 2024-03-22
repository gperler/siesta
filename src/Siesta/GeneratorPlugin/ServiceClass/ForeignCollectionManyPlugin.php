<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use ReflectionException;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\CollectionMany;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ForeignCollectionManyPlugin extends BasePlugin
{
    const METHOD_SELECT_NAME = "get%sJoin%s";

    const METHOD_DELETE_NAME = "delete%sJoin%s";

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getEntityByReferenceName(CollectionMany $collectionMany): string
    {
        $entityName = $collectionMany->getForeignEntity()->getClassShortName();
        $mappingName = $collectionMany->getMappingEntity()->getClassShortName();
        return sprintf(self::METHOD_SELECT_NAME, $entityName, $mappingName);
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function deleteEntityByReferenceName(CollectionMany $collectionMany): string
    {
        $entityName = $collectionMany->getForeignEntity()->getClassShortName();
        $mappingName = $collectionMany->getMappingEntity()->getClassShortName();
        return sprintf(self::METHOD_DELETE_NAME, $entityName, $mappingName);

    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @throws ReflectionException
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        foreach ($this->entity->getForeignCollectionManyList() as $collectionMany) {
            $this->generateAccessMethod($collectionMany);
            $this->generateDeleteMethod($collectionMany);
        }
    }

    /**
     * @param CollectionMany $collectionMany
     * @throws ReflectionException
     */
    protected function generateAccessMethod(CollectionMany $collectionMany): void
    {

        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();
        $entityClass = $this->entity->getInstantiationClassShortName();

        $methodName = self::getEntityByReferenceName($collectionMany);
        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($pkAttributeList);
        $helper->addConnectionNameParameter();
        $method->setReturnType($entityClass . '[]');

        $helper->addQuoteAttributeList($pkAttributeList, true);

        $spName = StoredProcedureNaming::getSelectByCollectionManyName($collectionMany);
        $signature = $helper->getSPInvocationSignature($pkAttributeList);
        $method->addCodeLine('return $this->executeStoredProcedure("CALL ' . $spName . '(' . $signature . ')", $connectionName);');
    }

    /**
     * @param CollectionMany $collectionMany
     * @throws ReflectionException
     */
    protected function generateDeleteMethod(CollectionMany $collectionMany): void
    {
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();
        $methodName = self::deleteEntityByReferenceName($collectionMany);

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($pkAttributeList);
        $helper->addConnectionNameParameter();

        $helper->addQuoteAttributeList($pkAttributeList, true);

        $spName = StoredProcedureNaming::getDeleteByCollectionManyName($collectionMany);
        $signature = $helper->getSPInvocationSignature($pkAttributeList);
        $method->addCodeLine('$connection->execute("CALL ' . $spName . '(' . $signature . ')");');
    }

}