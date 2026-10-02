<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Nitria\ScalarType;
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
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $classGenerator->addConstant("TABLE_NAME", '"' . $entity->getTableName() . '"', ScalarType::String);

        if ($entity->getIsDelimit()) {
            $classGenerator->addConstant("DELIMIT_TABLE_NAME", '"' . $entity->getDelimitTableName() . '"', ScalarType::String);
        }
        foreach ($entity->getAttributeList() as $attribute) {
            $constantName = strtoupper("COLUMN_" . $attribute->getPhpName());

            $classGenerator->addConstant($constantName, '"' . $attribute->getDBName() . '"', ScalarType::String);
        }
    }

}
