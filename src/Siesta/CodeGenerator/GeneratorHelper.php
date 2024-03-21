<?php

declare(strict_types = 1);

namespace Siesta\CodeGenerator;

use Civis\Common\ArrayUtil;
use Nitria\Method;
use ReflectionException;
use Siesta\Model\Attribute;
use Siesta\Model\DBType;
use Siesta\Model\PHPType;
use Siesta\Model\ReferenceMapping;

class GeneratorHelper
{

    const TYPE_ESCAPE_MAPPING = [
        PHPType::BOOL => "quoteBool",
        PHPType::INT => "quoteInt",
        PHPType::FLOAT => "quoteFloat",
        PHPType::SIESTA_DATE_TIME => "getDateTime"
    ];

    const DB_TYPE_ESCAPE_MAPPING = [
        "DATETIME" => "quoteDateTime",
        "DATE" => "quoteDate",
        "TIME" => "quoteTime"
    ];

    /**
     * @var Method
     */
    protected Method $method;

    /**
     * GeneratorHelper constructor.
     *
     * @param Method $method
     */
    public function __construct(Method $method)
    {
        $this->method = $method;
    }

    public function addConnectionNameParameter(): void
    {
        $this->method->addParameter(PHPType::STRING, "connectionName", "null");
    }

    /**
     * @param ReferenceMapping[] $mappingList
     * @param string|null $defaultValue
     */
    public function addReferenceMappingListParameter(array $mappingList, string $defaultValue = null): void
    {
        foreach ($mappingList as $mapping) {
            $this->addReferenceMappingParameter($mapping, $defaultValue);
        }
    }

    /**
     * @param ReferenceMapping $mapping
     * @param string|null $defaultValue
     */
    public function addReferenceMappingParameter(ReferenceMapping $mapping, string $defaultValue = null): void
    {
        $this->addAttributeParameter($mapping->getLocalAttribute(), $defaultValue);
    }


    /**
     * @param array $attributeList
     * @param string|null $defaultValue
     */
    public function addAttributeParameterList(array $attributeList, string $defaultValue = null): void
    {
        foreach ($attributeList as $attribute) {
            $this->addAttributeParameter($attribute, $defaultValue);
        }
    }

    /**
     * @param Attribute $attribute
     * @param string|null $defaultValue
     */
    public function addAttributeParameter(Attribute $attribute, string $defaultValue = null): void
    {
        $this->method->addParameter($attribute->getPhpType(), $attribute->getPhpName(), $defaultValue);
    }

    /**
     *
     */
    public function addConnectionLookup(): void
    {
        $this->method->addCodeLine('$connection = ConnectionFactory::getConnection($connectionName);');
    }


    /**
     * @param ReferenceMapping[] $referenceMappingList
     * @param bool $forceConnectionLookup
     * @param bool $fromMember
     * @throws ReflectionException
     */
    public function addQuoteReferenceMappingList(array $referenceMappingList, bool $forceConnectionLookup = false, bool $fromMember = false): void
    {
        $attributeList = [];
        foreach ($referenceMappingList as $referenceMapping) {
            $attributeList[] = $referenceMapping->getLocalAttribute();
        }
        $this->addQuoteAttributeList($attributeList, $forceConnectionLookup, $fromMember);
    }

    /**
     * @param string $phpType
     * @param string|null $dbType
     * @param string $variableName
     * @param bool $isObject
     * @param int|null $maxLength
     */
    public function addQuoteCall(string $phpType, ?string $dbType, string $variableName, bool $isObject = false, int $maxLength = null): void
    {
        $assignment = $variableName . ' = ';
        $quoteCall = $this->generateQuoteCall($phpType, $dbType, $variableName, $isObject, $maxLength);
        $this->method->addCodeLine($assignment . $quoteCall . ';');
    }

    /**
     * @param Attribute[] $attributeList
     * @param bool $forceConnectionLookup
     * @param bool $fromMember
     * @throws ReflectionException
     */
    public function addQuoteAttributeList(array $attributeList, bool $forceConnectionLookup = false, bool $fromMember = false): void
    {
        $hasString = false;
        foreach ($attributeList as $attribute) {
            if ($attribute->getPhpType() === PHPType::STRING) {
                $hasString = true;
            }
        }

        if ($hasString || $forceConnectionLookup) {
            $this->addConnectionLookup();
        }

        foreach ($attributeList as $attribute) {
            $name = $attribute->getPhpName();
            $variableName = '$' . $name;
            $source = ($fromMember) ? '$this->' . $name : $variableName;
            $quoteCall = $this->generateQuoteCall($attribute->getPhpType(), $attribute->getDbType(), $source, $attribute->getIsObject(), null, $attribute->implementsArraySerializable());
            $this->method->addCodeLine($variableName . ' = ' . $quoteCall . ';');
        }
    }

    /**
     * @param string $phpType
     * @param string|null $dbType
     * @param string $variableName
     * @param bool $isObject
     * @param int|null $maxLength
     * @param bool $arraySerializable
     * @return string
     */
    public function generateQuoteCall(string $phpType, ?string $dbType, string $variableName, bool $isObject = false, int $maxLength = null, bool $arraySerializable = false) : string
    {
        if ($isObject && $arraySerializable) {
            return 'Escaper::quoteArraySerializable($connection, ' . $variableName . ')';
        }

        if ($isObject) {
            return 'Escaper::quoteObject($connection,' . $variableName . ')';
        }

        if ($dbType === DBType::DATETIME) {
            return 'Escaper::quoteDateTime(' . $variableName . ')';
        }
        if ($dbType === DBType::DATE) {
            return 'Escaper::quoteDate(' . $variableName . ')';
        }
        if ($dbType === DBType::TIME) {
            return 'Escaper::quoteTime(' . $variableName . ')';
        }

        $quoteFunction = ArrayUtil::getFromArray(self::TYPE_ESCAPE_MAPPING, $phpType);
        if ($quoteFunction !== null) {
            return 'Escaper::' . $quoteFunction . '(' . $variableName . ')';
        }

        if ($phpType === PHPType::ARRAY) {
            return 'Escaper::quoteArray($connection, ' . $variableName . ')';
        }

        if ($maxLength !== 0 && $maxLength !== null) {
            return 'Escaper::quoteString($connection, ' . $variableName . ', ' . $maxLength . ')';
        }
        return 'Escaper::quoteString($connection, ' . $variableName . ' )';
    }

    /**
     * @param ReferenceMapping[] $referenceMappingList
     *
     * @return string
     */
    public function getSPInvocationSignatureFromReferenceMapping(array $referenceMappingList) : string
    {
        $attributeList = [];
        foreach ($referenceMappingList as $reference) {
            $attributeList[] = $reference->getLocalAttribute();
        }
        return $this->getSPInvocationSignature($attributeList);
    }

    /**
     * @param string $name
     * @param Attribute[] $attributeList
     * @param bool $result
     */
    public function addExecuteStoredProcedureWithAttributeList(string $name, array $attributeList, bool $result = true): void
    {
        $signature = $this->getSPInvocationSignature($attributeList);
        $this->addExecuteStoredProcedure($name, $signature, $result);
    }

    /**
     * @param Attribute[] $attributeList
     *
     * @return string
     */
    public function getSPInvocationSignature(array $attributeList) : string
    {
        $spParam = [];
        foreach ($attributeList as $attribute) {
            $spParam[] = '$' . $attribute->getPhpName();
        }
        return implode(",", $spParam);
    }

    /**
     * @param string $name
     * @param string $invocationSignature
     * @param bool $result
     */
    public function addExecuteStoredProcedure(string $name, string $invocationSignature, bool $result = true): void
    {
        if (!$result) {
            $this->method->addCodeLine('$connection->execute("CALL ' . $name . '(' . $invocationSignature . ')");');
        } else {
            $this->method->addCodeLine('$resultSet = $connection->execute("CALL ' . $name . '(' . $invocationSignature . ')");');
        }
    }



}