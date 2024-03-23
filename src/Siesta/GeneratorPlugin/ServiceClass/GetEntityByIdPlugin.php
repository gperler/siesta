<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Nitria\Method;
use ReflectionException;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Util\ArrayUtil;

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
    public function getUseClassNameList(Entity $entity): array
    {
        return [
            ArrayUtil::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList(): array
    {
        return [];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @throws ReflectionException
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        if (!$this->entity->hasPrimaryKey()) {
            return;
        }
        $this->generateEntityByPK();
    }

    /**
     * @throws ReflectionException
     */
    protected function generateEntityByPK(): void
    {
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $method = $this->classGenerator->addPublicMethod(self::METHOD_ENTITY_BY_PK);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($pkAttributeList, 'null');
        $method->setReturnType($this->entity->getInstantiationClassName(), true);

        $helper->addConnectionNameParameter();

        $this->generateCheckPrimaryKey($method);

        $helper->addQuoteAttributeList($pkAttributeList);

        $spName = StoredProcedureNaming::getSelectByPrimaryKeyName($this->entity);
        $signature = $helper->getSPInvocationSignature($pkAttributeList);
        $method->addCodeLine('$entityList = $this->executeStoredProcedure("CALL ' . $spName . '(' . $signature . ')", $connectionName);');
        $method->addCodeLine('return ArrayUtil::getFromArray($entityList, 0);');
    }

    /**
     * @param Method $generator
     */
    protected function generateCheckPrimaryKey(Method $generator): void
    {
        $pkCheckList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $pkCheckList[] = '$' . $pkAttribute->getPhpName() . ' === null';
        }
        $condition = implode(" || ", $pkCheckList);
        $generator->addIfStart($condition);

        $generator->addCodeLine('return null;');

        $generator->addIfEnd();
    }

}


