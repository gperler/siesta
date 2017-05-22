<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Attribute;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class FromResultSetPlugin extends BasePlugin
{

    const METHOD_FROM_RESULT_SET = "fromResultSet";

    const METHOD_GET_ADDITIONAL_COLUMN = "getAdditionalColumn";

    const RESULT_SET_ACCESS_MAPPING = [
        PHPType::BOOL => "getBooleanValue",
        PHPType::INT => "getIntegerValue",
        PHPType::FLOAT => "getFloatValue",
        PHPType::STRING => "getStringValue",
        PHPType::SIESTA_DATE_TIME => "getDateTime",
        PHPType::ARRAY => "getArray"
    ];

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [
            'Civis\Common\ArrayUtil'
        ];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);
        $this->addProperty();
        $this->generateFromResultSetMethod();
        $this->generateGetAdditionalColumn();
    }

    /**
     *
     */
    protected function addProperty()
    {
        $this->classGenerator->addProtectedProperty("_rawSQLResult", "array");
    }

    /**
     *
     */
    protected function generateFromResultSetMethod()
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_FROM_RESULT_SET);
        $method->addParameter('Siesta\Database\ResultSet', 'resultSet');

        $method->addCodeLine('$this->_existing = true;');
        $method->addCodeLine('$this->_rawSQLResult = $resultSet->getNext();');

        foreach ($this->entity->getAttributeList() as $attribute) {
            if ($attribute->getIsTransient()) {
                continue;
            }
            if ($attribute->implementsArraySerializable() && $attribute->getIsObject()) {

                $type = $attribute->getPhpType();

                $method->addCodeLine('$' .$attribute->getPhpName() . 'Array = $resultSet->getArray("' . $attribute->getDBName() . '");' );
                $method->addIfStart('$' .$attribute->getPhpName() . "Array !== null");
                $method->addCodeLine('$this->' . $attribute->getPhpName() . '= new ' . $type . '();');
                $method->addCodeLine('$this->' . $attribute->getPhpName() . '->fromArray($' . $attribute->getPhpName() . 'Array );');

                $method->addIfEnd();
                continue;
            }


            $assignment = $this->getAssignment($attribute);
            $method->addCodeLine($assignment);
        }
    }

    /**
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function getAssignment(Attribute $attribute) : string
    {
        $method = ArrayUtil::getFromArray(self::RESULT_SET_ACCESS_MAPPING, $attribute->getPhpType());
        if ($method === null && $attribute->implementsArraySerializable()) {
            $method = $attribute->implementsArraySerializable() ? "getArray" : "getObject";
        }

        return '$this->' . $attribute->getPhpName() . ' = $resultSet->' . $method . '("' . $attribute->getDBName() . '");';
    }

    /**
     *
     */
    protected function generateGetAdditionalColumn()
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_GET_ADDITIONAL_COLUMN);
        $method->addParameter(PHPType::STRING, 'key');
        $method->setReturnType(PHPType::STRING, true);

        $method->addCodeLine('return ArrayUtil::getFromArray($this->_rawSQLResult, $key);');
    }

}