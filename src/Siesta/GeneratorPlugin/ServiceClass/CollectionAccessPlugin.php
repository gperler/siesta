<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
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
    public static function getSelectByReferenceName(Reference $reference) : string
    {
        return sprintf(self::METHOD_NAME_SELECT_BY_REFERENCE, $reference->getMethodName());
    }

    /**
     * @param Reference $reference
     *
     * @return string
     */
    public static function getDeleteByReferenceName(Reference $reference) : string
    {
        return sprintf(self::METHOD_NAME_DELETE_BY_REFERENCE, $reference->getMethodName());

    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useList = [];
        foreach ($entity->getReferenceList() as $reference) {
            if (!$reference->doesCollectionRefersTo()) {
                continue;
            }
            $foreignEntity = $reference->getForeignEntity();
            $useList[] = $foreignEntity->getInstantiationClass();
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
     */
    protected function generateSelectByReference(Reference $reference)
    {

        $foreignEntity = $reference->getForeignEntity();

        $methodName = self::getSelectByReferenceName($reference);
        $mappingList = $reference->getReferenceMappingList();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addReferenceMappingListParameter($mappingList);
        $method->addConnectionNameParameter();
        $method->setReturnType($foreignEntity->getInstantiationClassShortName() . '[]');

        $method->addQuoteReferenceMappingList($mappingList, true);

        // stored procedure invocation
        $spName = StoredProcedureNaming::getSelectByReferenceName($this->entity, $reference);
        $signature = $method->getSPInvocationSignatureFromReferenceMapping($mappingList);
        $method->addLine('return $this->executeStoredProcedure("CALL ' . $spName . ' (' . $signature . ')", $connectionName);');

        $method->end();
    }

    /**
     * @param Reference $reference
     */
    protected function generateDeleteByReference(Reference $reference)
    {

        $methodName = self::getDeleteByReferenceName($reference);
        $mappingList = $reference->getReferenceMappingList();
        $pkList = $this->entity->getPrimaryKeyAttributeList();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addReferenceMappingListParameter($mappingList);
        $method->addAttributeParameterList($pkList, 'null');
        $method->addConnectionNameParameter();

        $method->addQuoteReferenceMappingList($mappingList, true);
        $method->addQuoteAttributeList($pkList, false);

        // stored procedure invocation
        $spName = StoredProcedureNaming::getDeleteByReferenceName($this->entity, $reference);
        $signatureAttributeList = $this->getSignatureAttributeList($reference);
        $method->addExecuteStoredProcedureWithAttributeList($spName, $signatureAttributeList, false);

        $method->end();
    }

    /**
     * @param Reference $reference
     *
     * @return array
     */
    protected function getSignatureAttributeList(Reference $reference) : array
    {
        $attributeList = [];
        foreach ($reference->getReferenceMappingList() as $mapping) {
            $attributeList[] = $mapping->getLocalAttribute();
        }
        return array_merge($attributeList, $this->entity->getPrimaryKeyAttributeList());
    }

}