<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\DynamicCollectionAttributeList;
use Siesta\Model\Entity;

class DynamicCollectionAccessPlugin extends BasePlugin
{
    const NAME_ACCESS_ONE = "getDynamicCollectionOne";

    const NAME_ACCESS_MANY = "getDynamicCollectionMany";

    const NAME_DELETE = "deleteDynamicCollection";

    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);
        if (!$this->entity->getIsDynamicCollectionTarget()) {
            return;

        }

        $this->generateDynamicCollectionManyAccess();
        $this->generateDynamicCollectionDelete();
    }

    protected function generateDynamicCollectionManyAccess()
    {
        $dynamicAttributeList = DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this->entity);
        $className = $this->entity->getInstantiationClassName();

        $method = $this->classGenerator->addPublicMethod(self::NAME_ACCESS_MANY);
        $method->setReturnType($className . '[]');

        $helper = new GeneratorHelper($method);
        $helper->addAttributeParameterList($dynamicAttributeList);
        $helper->addConnectionNameParameter();

        $helper->addQuoteAttributeList($dynamicAttributeList);

        $spName = StoredProcedureNaming::getSelectByDynamicCollectionName($this->entity);
        $signature = $helper->getSPInvocationSignature($dynamicAttributeList);

        $method->addCodeLine('return $this->executeStoredProcedure("CALL ' . $spName . '(' . $signature . ')", $connectionName);');
    }

    protected function generateDynamicCollectionDelete()
    {
        $dynamicAttributeList = DynamicCollectionAttributeList::getDynamicCollectionAttributeList($this->entity);
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $attributeList = array_merge($dynamicAttributeList, $pkAttributeList);

        $method = $this->classGenerator->addPublicMethod(self::NAME_DELETE);

        $helper = new GeneratorHelper($method);
        $helper->addAttributeParameterList($dynamicAttributeList);
        $helper->addAttributeParameterList($pkAttributeList, "null");

        $helper->addConnectionNameParameter();

        $helper->addQuoteAttributeList($attributeList);

        $spName = StoredProcedureNaming::getDeleteByDynamicCollectionName($this->entity);
        $helper->addExecuteStoredProcedureWithAttributeList($spName, $attributeList, false);
    }

}