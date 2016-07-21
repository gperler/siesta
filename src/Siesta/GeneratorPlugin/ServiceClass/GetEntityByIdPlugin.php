<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\CodeGenerator\MethodGenerator;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class GetEntityByIdPlugin extends BasePlugin
{
    const METHOD_ENTITY_BY_PK = "getEntityByPrimaryKey";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return ['Siesta\Util\ArrayUtil'];
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

        if (!$this->entity->hasPrimaryKey()) {
            return;
        }
        $this->generateEntityByPK();
    }

    /**
     *
     */
    protected function generateEntityByPK()
    {
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $method = $this->codeGenerator->newPublicMethod(self::METHOD_ENTITY_BY_PK);
        $method->addAttributeParameterList($pkAttributeList, 'null');
        $method->setReturnType($this->entity->getInstantiationClassShortName(), true);

        $method->addConnectionNameParameter();

        $this->generateCheckPrimaryKey($method);

        $method->addQuoteAttributeList($pkAttributeList);

        $spName = StoredProcedureNaming::getSelectByPrimaryKeyName($this->entity);
        $signature = $method->getSPInvocationSignature($pkAttributeList);
        $method->addLine('$entityList = $this->executeStoredProcedure("CALL ' . $spName . '(' . $signature . ')", $connectionName);');
        $method->addLine('return ArrayUtil::getFromArray($entityList, 0);');

        $method->end();
    }

    /**
     * @param MethodGenerator $generator
     */
    protected function generateCheckPrimaryKey(MethodGenerator $generator)
    {
        $pkCheckList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $pkCheckList[] = '$' . $pkAttribute->getPhpName() . ' === null';
        }
        $condition = implode(" || ", $pkCheckList);
        $generator->addIfStart($condition);

        $generator->addLine('return null;');

        $generator->addIfEnd();
    }

}


