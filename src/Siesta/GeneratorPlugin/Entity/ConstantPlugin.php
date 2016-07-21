<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ConstantPlugin extends BasePlugin
{
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
        $codeGenerator->addConstant("TABLE_NAME", $entity->getTableName());
        if ($entity->getIsDelimit()) {
            $codeGenerator->addConstant("DELIMIT_TABLE_NAME", $entity->getTableName());
        }
        foreach ($entity->getAttributeList() as $attribute) {
            $codeGenerator->addConstant("COLUMN_" . $attribute->getPhpName(), $attribute->getDBName());
        }
    }

}
