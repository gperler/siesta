<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
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
            'Siesta\Database\ResultSet',
            'Siesta\Util\ArrayUtil'
        ];
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
        $this->generateFromResultSetMethod();
        $this->generateGetAdditionalColumn();
    }

    /**
     *
     */
    protected function generateFromResultSetMethod()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_FROM_RESULT_SET);
        $method->addParameter('ResultSet', 'resultSet');

        $method->addLine('$this->_existing = true;');
        $method->addLine('$this->_rawSQLResult = $resultSet->getNext();');

        foreach ($this->entity->getAttributeList() as $attribute) {
            $assignment = $this->getAssignment($attribute);
            $method->addLine($assignment);
        }

        $method->end();
    }

    /**
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function getAssignment(Attribute $attribute) : string
    {
        $method = ArrayUtil::getFromArray(self::RESULT_SET_ACCESS_MAPPING, $attribute->getPhpType());
        if ($method === null) {
            $method = "getObject";
        }
        return '$this->' . $attribute->getPhpName() . ' = $resultSet->' . $method . '("' . $attribute->getDBName() . '");';
    }

    /**
     *
     */
    protected function generateGetAdditionalColumn()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_GET_ADDITIONAL_COLUMN);
        $method->addParameter(PHPType::STRING, 'key');
        $method->setReturnType(PHPType::STRING, true);

        $method->addLine('return ArrayUtil::getFromArray($this->_rawSQLResult, $key);');
        $method->end();

    }

}