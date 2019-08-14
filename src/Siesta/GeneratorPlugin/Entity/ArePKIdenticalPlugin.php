<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

class ArePKIdenticalPlugin extends BasePlugin
{
    const METHOD_ARE_PK_IDENTICAL = "arePrimaryKeyIdentical";


    /**
     * @return string[]
     */
    public function getInterfaceList(): array
    {
        return ['Siesta\Contract\Comparable'];
    }


    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);
        $this->generateArePKIdentical();
    }

    /**
     *
     */
    protected function generateArePKIdentical()
    {

        $method = $this->classGenerator->addPublicMethod(self::METHOD_ARE_PK_IDENTICAL);

        $method->addParameter('Siesta\Contract\Comparable', 'entity', 'null');
        $method->setReturnType('bool', false);

        $method->addIfStart('$entity === null');
        $method->addCodeLine('return false;');
        $method->addIfEnd();

        $method->addIfStart('$entity instanceof self');
        $method->addCodeLine('return ' . $this->getCompareExpression() . ';');
        $method->addIfEnd();

        $method->addCodeLine('return false;');
    }

    /**
     * @return string
     */
    protected function getCompareExpression(): string
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