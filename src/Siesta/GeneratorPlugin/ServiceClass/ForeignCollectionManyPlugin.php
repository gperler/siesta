<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
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
    public static function getEntityByReferenceName(CollectionMany $collectionMany) : string
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
    public static function deleteEntityByReferenceName(CollectionMany $collectionMany) : string
    {
        $entityName = $collectionMany->getForeignEntity()->getClassShortName();
        $mappingName = $collectionMany->getMappingEntity()->getClassShortName();
        return sprintf(self::METHOD_DELETE_NAME, $entityName, $mappingName);

    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [];
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

        foreach ($this->entity->getForeignCollectionManyList() as $collectionMany) {
            $this->generateAccessMethod($collectionMany);
            $this->generateDeleteMethod($collectionMany);
        }
    }

    /**
     * @param CollectionMany $collectionMany
     */
    protected function generateAccessMethod(CollectionMany $collectionMany)
    {

        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();
        $entityClass = $this->entity->getInstantiationClassShortName();

        $methodName = self::getEntityByReferenceName($collectionMany);
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addAttributeParameterList($pkAttributeList);
        $method->addConnectionNameParameter();

        $method->addConnectionLookup();
        $method->setReturnType($entityClass . '[]');

        $method->addQuoteAttributeList($pkAttributeList, false);

        $spName = StoredProcedureNaming::getSelectByCollectionManyName($collectionMany);
        $signature = $method->getSPInvocationSignature($pkAttributeList);
        $method->addLine('return $this->executeStoredProcedure("CALL ' . $spName . '(' . $signature . ')", $connectionName);');

        $method->end();
    }

    /**
     * @param CollectionMany $collectionMany
     */
    protected function generateDeleteMethod(CollectionMany $collectionMany)
    {
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();
        $methodName = self::deleteEntityByReferenceName($collectionMany);

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addAttributeParameterList($pkAttributeList);
        $method->addConnectionNameParameter();

        $method->addQuoteAttributeList($pkAttributeList, true);

        $spName = StoredProcedureNaming::getDeleteByCollectionManyName($collectionMany);
        $signature = $method->getSPInvocationSignature($pkAttributeList);
        $method->addLine('$connection->execute("CALL ' . $spName . '(' . $signature . ')");');

        $method->end();
    }

}