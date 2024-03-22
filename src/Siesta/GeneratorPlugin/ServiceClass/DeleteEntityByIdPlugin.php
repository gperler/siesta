<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use ReflectionException;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class DeleteEntityByIdPlugin extends BasePlugin
{
    const METHOD_DELETE_ENTITY_BY_PK = "deleteEntityByPrimaryKey";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity): array
    {
        return [ArrayUtil::class];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @throws ReflectionException
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        if (!$entity->hasPrimaryKey()) {
            return;
        }

        $this->generateDeleteEntityByPK();
    }

    /**
     * @throws ReflectionException
     */
    protected function generateDeleteEntityByPK(): void
    {
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $method = $this->classGenerator->addPublicMethod(self::METHOD_DELETE_ENTITY_BY_PK);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($pkAttributeList);
        $helper->addConnectionNameParameter();

        $helper->addQuoteAttributeList($pkAttributeList, true);

        $spName = StoredProcedureNaming::getDeleteByPrimaryKeyName($this->entity);
        $helper->addExecuteStoredProcedureWithAttributeList($spName, $pkAttributeList, false);
    }

}


