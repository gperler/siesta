<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Attribute;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

/**
 * @author Gregor Müller
 */
class AttributeGetterSetterPlugin extends BasePlugin
{
    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useList = [];
        foreach ($entity->getAttributeList() as $attribute) {
            if ($attribute->getPhpType() === PHPType::STRING) {
                $useList[] = 'Siesta\Util\StringUtil';
            }
            if ($attribute->getAutoValue() !== null) {
                $useList[] = 'Siesta\Sequencer\SequencerFactory';
            }
        }
        return $useList;
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return [
            'Siesta\GeneratorPlugin\Entity\MemberPlugin',
            'Siesta\GeneratorPlugin\Entity\ConstantPlugin'
        ];
    }

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);

        foreach ($entity->getAttributeList() as $attribute) {
            if ($attribute->getIsPrimaryKey()) {
                $this->generatePrimaryKeyGetter($attribute);
            } else {
                $this->generateGetter($attribute);
            }

            $this->generateSetter($attribute, $codeGenerator);

            if ($attribute->getPhpType() === PHPType::ARRAY) {
                $this->generateAddTo($attribute, $codeGenerator);
                $this->generateGetFrom($attribute, $codeGenerator);
            }

        }
    }

    /**
     * @param Attribute $attribute
     */
    protected function generateGetter(Attribute $attribute)
    {
        $methodName = "get" . $attribute->getMethodName();
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->setReturnType($attribute->getPhpType(), true);

        $method->addLine('return $this->' . $attribute->getPhpName() . ';');
        $method->end();

    }

    /**
     * @param Attribute $attribute
     */
    protected function generatePrimaryKeyGetter(Attribute $attribute)
    {
        $methodName = "get" . $attribute->getMethodName();
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter(PHPType::BOOL, 'generateKey', 'false');
        $method->addParameter(PHPType::STRING, 'connectionName', 'null');
        $method->setReturnType($attribute->getPhpType(), true);
        $autoValue = $attribute->getAutoValue();

        $memberName = '$this->' . $attribute->getPhpName();

        $method->addIfStart('$generateKey && ' . $memberName . ' === null');
        $method->addLine($memberName . ' = SequencerFactory::nextSequence("' . $autoValue . '", self::TABLE_NAME, $connectionName);');
        $method->addIfEnd();

        $method->addLine('return ' . $memberName . ';');
        $method->end();
    }

    /**
     * @param Attribute $attribute
     * @param CodeGenerator $codeGenerator
     */
    protected function generateSetter(Attribute $attribute, CodeGenerator $codeGenerator)
    {
        $name = $attribute->getPhpName();
        $type = $attribute->getPhpType();
        $methodName = "set" . $attribute->getMethodName();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter($type, $name, 'null');

        if ($type === PHPType::STRING) {

            $length = $attribute->getLength() !== null ? $attribute->getLength() : 'null';
            $method->addLine('$this->' . $name . ' = StringUtil::trimToNull($' . $name . ", " . $length . ");");
        } else {
            $method->addLine('$this->' . $name . ' = $' . $name . ";");
        }

        $method->end();

    }

    /**
     * @param Attribute $attribute
     * @param CodeGenerator $codeGenerator
     */
    protected function generateAddTo(Attribute $attribute, CodeGenerator $codeGenerator)
    {
        $methodName = "addTo" . $attribute->getMethodName();
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter(PHPType::STRING, "key");
        $method->addParameter("", "value");

        $memberName = '$this->' . $attribute->getPhpName();

        $method->addIfStart($memberName . ' === null');
        $method->addLine($memberName . ' = [];');
        $method->addIfEnd();

        $method->addLine($memberName . '[$key] = $value;');

        $method->end();
    }

    protected function generateGetFrom(Attribute $attribute, CodeGenerator $codeGenerator)
    {
        $methodName = "getFrom" . $attribute->getMethodName();
        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter(PHPType::STRING, "key");
        $method->setReturnType('mixed', true);
        $memberName = '$this->' . $attribute->getPhpName();

        $method->addLine('return ArrayUtil::getFromArray(' . $memberName . ', $key);');

        $method->end();

    }

}