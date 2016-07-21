<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ArePKIdenticalPlugin extends BasePlugin
{
    const METHOD_ARE_PK_IDENTICAL = "arePrimaryKeyIdentical";

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
        $this->setup($entity, $codeGenerator);
        $this->generateArePKIdentical();
    }

    /**
     *
     */
    protected function generateArePKIdentical()
    {
        $className = $this->entity->getClassShortName();

        $method = $this->codeGenerator->newPublicMethod(self::METHOD_ARE_PK_IDENTICAL);
        $method->addParameter($className, 'entity', 'null');
        $method->setReturnType('bool');

        // check for null
        $method->addIfStart('$entity === null');
        $method->addLine('return false;');
        $method->addIfEnd();

        $method->addLine('return ' . $this->getExpression() . ';');
        $method->end();
    }

    /**
     * @return string
     */
    protected function getExpression() : string
    {
        if (!$this->entity->hasPrimaryKey()) {
            return 'false';
        }
        $pkList = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $methodName = $pkAttribute->getMethodName();
            $pkList[] = '$this->get' . $methodName . '() === $entity->get' . $methodName . '()';
        }
        return implode(" && ", $pkList);
    }
}