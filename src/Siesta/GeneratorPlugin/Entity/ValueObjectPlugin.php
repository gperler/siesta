<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
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

    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);

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
        $this->codeGenerator->addProtectedMember($valueObject->getPhpName(), $valueObject->getClassShortName());
    }

    protected function generateValueObjectGetter(ValueObject $valueObject)
    {
        $memberName = '$this->' . $valueObject->getPhpName();
        $classShortName = $valueObject->getClassShortName();

        $method = $this->codeGenerator->newPublicMethod('get' . $valueObject->getMethodName());
        $method->setReturnType($classShortName, true);

        $method->addIfStart($memberName . ' === null');

        $method->addLine($memberName . ' = new ' . $classShortName . '();');

        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addLine($memberName . '->set' . $methodName . '($this->get' . $methodName . '());');
        }

        $method->addIfEnd();

        $method->addLine('return ' . $memberName . ';');

        $method->end();
    }

    protected function generateValueObjectSetter(ValueObject $valueObject)
    {
        $memberName = '$this->' . $valueObject->getPhpName();
        $classShortName = $valueObject->getClassShortName();

        $method = $this->codeGenerator->newPublicMethod('set' . $valueObject->getMethodName());
        $method->addParameter($classShortName, 'valueObject', 'null');

        $method->addIfStart('$valueObject === null');
        $method->addLine($memberName . ' = null;');
        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addLine('$this->set' . $methodName . '(null);');
        }
        $method->addLine('return;');
        $method->addIfEnd();

        $method->addIfStart($memberName . ' === null');

        $method->addLine($memberName . ' = new ' . $classShortName . '();');

        $method->addIfEnd();

        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addLine('$this->set' . $methodName . '($valueObject->get' . $methodName . '());');
        }
        foreach ($valueObject->getAttributeList() as $attribute) {
            $methodName = $attribute->getMethodName();
            $method->addLine($memberName . '->set' . $methodName . '($valueObject->get' . $methodName . '());');
        }


        $method->end();

    }

}