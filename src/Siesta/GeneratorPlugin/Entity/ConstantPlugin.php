<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ConstantPlugin extends BasePlugin
{

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $classGenerator->addConstant("TABLE_NAME", '"' . $entity->getTableName() . '"');

        if ($entity->getIsDelimit()) {
            $classGenerator->addConstant("DELIMIT_TABLE_NAME", '"' . $entity->getTableName() . '"');
        }
        foreach ($entity->getAttributeList() as $attribute) {
            $constantName = strtoupper("COLUMN_" . $attribute->getPhpName());

            $classGenerator->addConstant($constantName, '"' . $attribute->getDBName() . '"');
        }
    }

}
