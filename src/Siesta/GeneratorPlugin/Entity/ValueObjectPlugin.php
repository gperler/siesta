<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\ValueObject;

class ValueObjectPlugin extends BasePlugin
{

    /**
     * @param Entity $entity
     *
     * @return array
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useStatementList = [];

        foreach ($entity->getValueObjectList() as $valueObject) {
            $useStatementList[] = $valueObject->getClassName();
        }

        return $useStatementList;
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);

        $this->generateMemberList();
        foreach ($this->entity->getValueObjectList() as $valueObject) {
            $this->generateValueObjectGetter($valueObject);
            $this->generateValueObjectSetter($valueObject);
        }
    }

    /**
     *
     */
    protected function generateMemberList()
    {
        foreach ($this->entity->getValueObjectList() as $valueObject) {
            $this->generateMember($valueObject);
        }
    }

    /**
     * @param ValueObject $valueObject
     */
    protected function generateMember(ValueObject $valueObject)
    {
        $this->classGenerator->addProtectedProperty($valueObject->getPhpName(), $valueObject->getClassName());
    }

    protected function generateValueObjectGetter(ValueObject $valueObject)
    {
        $memberName = '$this->' . $valueObject->getPhpName();
        $classShortName = $valueObject->getClassShortName();

        $method = $this->classGenerator->addPublicMethod('get' . $valueObject->getMethodName());
        $method->setReturnType($valueObject->getClassName(), true);

        $method->addIfStart($memberName . ' === null');

        $method->addCodeLine($memberName . ' = new ' . $classShortName . '();');

        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addCodeLine($memberName . '->set' . $methodName . '($this->get' . $methodName . '());');
        }

        $method->addIfEnd();

        $method->addCodeLine('return ' . $memberName . ';');
    }

    protected function generateValueObjectSetter(ValueObject $valueObject)
    {
        $memberName = '$this->' . $valueObject->getPhpName();
        $classShortName = $valueObject->getClassShortName();

        $method = $this->classGenerator->addPublicMethod('set' . $valueObject->getMethodName());
        $method->addParameter($valueObject->getClassName(), 'valueObject', 'null');

        $method->addIfStart('$valueObject === null');
        $method->addCodeLine($memberName . ' = null;');
        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addCodeLine('$this->set' . $methodName . '(null);');
        }
        $method->addCodeLine('return;');
        $method->addIfEnd();

        $method->addIfStart($memberName . ' === null');

        $method->addCodeLine($memberName . ' = new ' . $classShortName . '();');

        $method->addIfEnd();

        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addCodeLine('$this->set' . $methodName . '($valueObject->get' . $methodName . '());');
        }
        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addCodeLine($memberName . '->set' . $methodName . '($valueObject->get' . $methodName . '());');
        }
    }

}