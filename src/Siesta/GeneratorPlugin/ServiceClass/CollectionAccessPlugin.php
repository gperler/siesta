<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use ReflectionException;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
class CollectionAccessPlugin extends BasePlugin
{
    const METHOD_NAME_SELECT_BY_REFERENCE = "getEntityBy%sReference";

    const METHOD_NAME_DELETE_BY_REFERENCE = "deleteEntityBy%sReference";

    /**
     * @param Reference $reference
     *
     * @return string
     */
    public static function getSelectByReferenceName(Reference $reference): string
    {
        return sprintf(self::METHOD_NAME_SELECT_BY_REFERENCE, $reference->getMethodName());
    }

    /**
     * @param Reference $reference
     *
     * @return string
     */
    public static function getDeleteByReferenceName(Reference $reference): string
    {
        return sprintf(self::METHOD_NAME_DELETE_BY_REFERENCE, $reference->getMethodName());

    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity): array
    {
        $useList = [];
        foreach ($entity->getReferenceList() as $reference) {
            if (!$reference->doesCollectionRefersTo()) {
                continue;
            }
            $foreignEntity = $reference->getForeignEntity();
            $useList[] = $foreignEntity->getInstantiationClassName();
        }
        return $useList;
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @throws ReflectionException
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        foreach ($this->entity->getReferenceList() as $reference) {
            if (!$reference->doesCollectionRefersTo()) {
                continue;
            }
            $this->generateSelectByReference($reference);
            $this->generateDeleteByReference($reference);
        }
    }

    /**
     * @param Reference $reference
     * @throws ReflectionException
     */
    protected function generateSelectByReference(Reference $reference): void
    {

        $foreignEntity = $reference->getForeignEntity();

        $methodName = self::getSelectByReferenceName($reference);
        $mappingList = $reference->getReferenceMappingList();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addReferenceMappingListParameter($mappingList);
        $helper->addConnectionNameParameter();
        $method->setReturnType($foreignEntity->getInstantiationClassName() . '[]');

        $helper->addQuoteReferenceMappingList($mappingList, true);

        // stored procedure invocation
        $spName = StoredProcedureNaming::getSelectByReferenceName($this->entity, $reference);
        $signature = $helper->getSPInvocationSignatureFromReferenceMapping($mappingList);
        $method->addCodeLine('return $this->executeStoredProcedure("CALL ' . $spName . ' (' . $signature . ')", $connectionName);');
    }

    /**
     * @param Reference $reference
     * @throws ReflectionException
     */
    protected function generateDeleteByReference(Reference $reference): void
    {

        $methodName = self::getDeleteByReferenceName($reference);
        $mappingList = $reference->getReferenceMappingList();
        $pkList = $this->entity->getPrimaryKeyAttributeList();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addReferenceMappingListParameter($mappingList);
        $helper->addAttributeParameterList($pkList, 'null');
        $helper->addConnectionNameParameter();

        $helper->addQuoteReferenceMappingList($mappingList, true);
        $helper->addQuoteAttributeList($pkList, false);

        // stored procedure invocation
        $spName = StoredProcedureNaming::getDeleteByReferenceName($this->entity, $reference);
        $signatureAttributeList = $this->getSignatureAttributeList($reference);
        $helper->addExecuteStoredProcedureWithAttributeList($spName, $signatureAttributeList, false);
    }

    /**
     * @param Reference $reference
     *
     * @return array
     */
    protected function getSignatureAttributeList(Reference $reference): array
    {
        $attributeList = [];
        foreach ($reference->getReferenceMappingList() as $mapping) {
            $attributeList[] = $mapping->getLocalAttribute();
        }
        return array_merge($attributeList, $this->entity->getPrimaryKeyAttributeList());
    }

}