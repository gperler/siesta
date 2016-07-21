<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

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

        if (!$entity->hasPrimaryKey()) {
            return;
        }

        $this->generateDeleteEntityByPK();
    }

    /**
     *
     */
    protected function generateDeleteEntityByPK()
    {
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $method = $this->codeGenerator->newPublicMethod(self::METHOD_DELETE_ENTITY_BY_PK);
        $method->addAttributeParameterList($pkAttributeList);
        $method->addConnectionNameParameter();

        $method->addQuoteAttributeList($pkAttributeList, true);

        $spName = StoredProcedureNaming::getDeleteByPrimaryKeyName($this->entity);
        $method->addExecuteStoredProcedureWithAttributeList($spName, $pkAttributeList, false);

        $method->end();
    }

}


